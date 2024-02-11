const baseCountry = IntlPhoneNumberFormatData.base_country;
const fields = IntlPhoneNumberFormatData.fields;
const shippingCountries = IntlPhoneNumberFormatData.shipping_countries;
const allowedCountries = IntlPhoneNumberFormatData.allowed_countries;
const allCountries = IntlPhoneNumberFormatData.all_countries;
const validationMessages = IntlPhoneNumberFormatData.validations;
const patterns = IntlPhoneNumberFormatData.patterns;
const lookup = IntlPhoneNumberFormatData.lookup;
const inputHelperClass = IntlPhoneNumberFormatData.input_helper_class;
const apiUrl = "https://ipapi.co/json";

jQuery(document).ready(function ($) {
  getFields().forEach((entry) => {
    let field = entry.id;
    let required = entry.required;
    let countries = entry.countries;
    buildIntlPhoneNumberField(field, required, countries);
  });

  validateForms();
});

function validateForms() {
  checkoutPage();
  otherForms();
}

function checkoutPage() {
  let checkoutForm = jQuery("form.checkout");

  if (checkoutForm.length) {
    checkoutForm.on("checkout_place_order", function (event) {
      let isValid = true;

      getFields().forEach((entry) => {
        let field = entry.id;
        let required = entry.required;
        let type = entry.type;
        let isShippingField = type === "shipping";

        if (getInput(field) && required) {
          let isValidField = true;

          if (isShippingField) {
            let shipToDifferentAddress = document.querySelector(
              "#ship-to-different-address-checkbox"
            );
            if (
              shipToDifferentAddress &&
              shipToDifferentAddress.checked &&
              !validatePhoneNumber(field)
            ) {
              isValidField = false;
            }
          } else if (!validatePhoneNumber(field)) {
            isValidField = false;
          }

          if (!isValidField) {
            isValid = false;
          }
        }
      });

      if (!isValid) {
        event.preventDefault(); // Prevent form submission if any field is invalid
        return false;
      }
    });
  }

  return true;
}

function otherForms() {
  getFields().forEach((entry) => {
    let field = entry.id;
    let required = entry.required;
    let form = getFormByField(field);
    if (form && required) {
      form.addEventListener("submit", function (event) {
        let isValid = validatePhoneNumber(field);

        if (!isValid) {
          event.preventDefault();
          return false;
        }
        return isValid;
      });
    }
  });

  return true;
}

function getFormByField(field) {
  let input = getInput(field);
  if (!input) {
    return null;
  }

  let form = input;
  while (form && form.tagName !== "FORM") {
    form = form.parentElement;
  }

  return form;
}

function buildIntlPhoneNumberField(field, required, countries) {
  let inputID = "#" + field;
  let input = document.querySelector(inputID);
  if (input) {
    let configs = {
      hiddenInput: field,
      separateDialCode: true,
      nationalMode: true,
    };

    let preferredCountries = [];
    let defaultCountry = baseCountry;
    let onlyCountries = getCountriesBy(countries);

    if (!onlyCountries.includes(defaultCountry) && onlyCountries.length > 0) {
      defaultCountry = onlyCountries[0] ?? "";
    } else if (
      !onlyCountries.includes(defaultCountry) &&
      onlyCountries.length == 0
    ) {
      defaultCountry = defaultCountry;
    } else {
      preferredCountries = [defaultCountry];
    }

    let hasMultipleCountry = onlyCountries.length > 1;

    if (lookup.active && hasMultipleCountry) {
      let lockupCountry = getCountryFromLocalStorage();

      if (lockupCountry) {
        if (onlyCountries.includes(lockupCountry)) {
          if (lockupCountry != defaultCountry) {
            preferredCountries.unshift(lockupCountry);
          }
        }

        if (!onlyCountries.includes(lockupCountry)) {
          configs.initialCountry = defaultCountry;
        }
      } else {
        let lockupCountryCallback = (callback) => {
          fetch(apiUrl)
            .then((res) => res.json())
            .then((data) => {
              let country;
              if (
                data?.country_code &&
                onlyCountries.includes(data.country_code)
              ) {
                country = data.country_code;
                setCountryLocalStorage(country);
              } else {
                country = defaultCountry;
              }
              callback(country);
            })
            .catch(() => callback(defaultCountry));
        };

        configs.geoIpLookup = lockupCountryCallback;
        configs.initialCountry = "auto";
      }
    } else {
      configs.initialCountry = defaultCountry;
    }

    configs.onlyCountries = onlyCountries;
    configs.allowDropdown = hasMultipleCountry;
    configs.preferredCountries = preferredCountries;

    let iti = window.intlTelInput(input, configs);

    let filterInput = (event) => {
      let input = event.target;
      let cleanedValue = input.value.replace(/[^\d\s-]/g, "");
      cleanedValue = cleanedValue.trim();

      if (cleanedValue.length > 15) {
        cleanedValue = cleanedValue.substring(0, 15);
      }

      input.value = cleanedValue;
    };

    let handleChange = () => {
      if (required) {
        validateError(iti, input);
      }
    };

    input.addEventListener("input", filterInput);
    input.addEventListener("change", handleChange);
    input.addEventListener("keyup", handleChange);
    input.addEventListener("countrychange", handleChange);
    input.addEventListener("blur", handleChange);
    input.addEventListener("input", handleChange);
  }
}

function validatePhoneNumber(field) {
  let phoneInput = getInput(field);

  if (!phoneInput) {
    return false;
  }

  let iti = getFieldIntlInstance(field);
  if (!iti) {
    return false;
  }

  let hasError = false;
  let hasErrorMessage = validateError(iti, phoneInput);
  let validPhoneNumber = iti.isValidNumber();

  if (hasErrorMessage) {
    hasError = true;
  }

  if (!validPhoneNumber) {
    hasError = true;
  }

  if (hasError) {
    phoneInput.scrollIntoView({ behavior: "smooth" });
    return false;
  }

  return true;
}

function getFieldIntlInstance(field) {
  return window.intlTelInputGlobals.getInstance(getInput(field));
}

function getInput(field) {
  let inputID = "#" + field;
  return document.querySelector(inputID);
}

function validateError(iti, input) {
  let hasError = false;
  let text;

  if (input.value) {
    text = iti.isValidNumber()
      ? ""
      : validationMessages.invalid_phone_number_format;

    let fullNumber = iti.getNumber();

    Object.entries(patterns).forEach((patternEntry) => {
      let [prefix, regexString] = patternEntry;
      if (fullNumber.startsWith(prefix)) {
        let regex = new RegExp(regexString);
        if (!regex.test(fullNumber)) {
          text = validationMessages.invalid_phone_number_format;
          return;
        }
      }
    });
  } else {
    text = validationMessages.required_phone_number;
  }

  hasError = text !== "";

  let classArray = ["validate-required", "form-row"];
  let closestAncestor = null;

  for (let className of classArray) {
    closestAncestor = findClosestAncestor(input, className);
    if (closestAncestor) {
      break;
    }
  }

  if (closestAncestor) {
    closestAncestor.classList.remove(
      "woocommerce-invalid",
      "woocommerce-validated"
    );
    if (text !== "") {
      closestAncestor.classList.add("woocommerce-invalid");
    } else {
      closestAncestor.classList.add("woocommerce-validated");
    }
  }

  let existingHelpText = input.parentElement.nextElementSibling;
  if (existingHelpText) {
    existingHelpText.textContent = text;
    if (text) {
      existingHelpText.style.display = "block";
    } else {
      existingHelpText.style.display = "none";
    }
  } else {
    let errorMessage = document.createElement("span");
    errorMessage.className = inputHelperClass;
    errorMessage.textContent = text;
    if (text) {
      errorMessage.style.display = "block";
    } else {
      errorMessage.style.display = "none";
    }
    input.parentElement.insertAdjacentElement("afterend", errorMessage);
  }

  return hasError;
}

function findClosestAncestor(element, className) {
  let parent = element.parentElement;
  while (parent) {
    if (parent.classList.contains(className)) {
      return parent;
    }
    parent = parent.parentElement;
  }
  return null;
}

function getFields() {
  return fields.length
    ? fields
        .map((field) => ({
          ...field,
          element: document.getElementById(field.id),
        }))
        .filter((field) => field.element !== null)
        .sort((a, b) => {
          return compareElementPositions(a.element, b.element);
        })
    : [];
}

function compareElementPositions(elementA, elementB) {
  if (elementA === elementB) {
    return 0;
  }
  if (
    elementA.compareDocumentPosition(elementB) &
    Node.DOCUMENT_POSITION_PRECEDING
  ) {
    return -1;
  }
  return 1;
}

function getCountriesBy(countriesType) {
  let onlyCountries = [];
  if (countriesType === "billing") {
    onlyCountries = allowedCountries;
  } else if (countriesType === "shipping") {
    onlyCountries = shippingCountries;
  } else if (countriesType === "all") {
    onlyCountries = allCountries;
  }
  return onlyCountries;
}

function setCountryLocalStorage(countryCode) {
  let expiration = new Date().getTime() + 60 * 60 * 1000 * lookup.ttl;
  let data = { countryCode, expiration };
  localStorage.setItem(
    "intl_phone_number_lcokup_country",
    JSON.stringify(data)
  );
}

function getCountryFromLocalStorage() {
  let storedData = localStorage.getItem("intl_phone_number_lcokup_country");
  if (storedData) {
    let data = JSON.parse(storedData);
    if (data.expiration && new Date().getTime() < data.expiration) {
      return data.countryCode || "";
    }
  }
  return "";
}
