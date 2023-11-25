$(function () {
  $(".simap_input-numeric").each(function () {
    var input = $(this).data("value");
    input = input ? parseInt(input, 10) : 0;

    $(this).val(function () {
      return input === 0 ? "" : number_format(input);
    });
  });
});

$(document).on("input keypress", ".simap_input-numeric", function (event) {
  var selection = window.getSelection().toString();

  if (selection !== "") return;

  if ($.inArray(event.keyCode, [37, 38, 39, 40]) !== -1) return;

  var input = $(this).val().replace(/,/g, "");
  input = input ? parseInt(input, 10) : 0;

  $(this).val(function () {
    return input === 0 ? "" : number_format(input);
  });
});

function number_format(number, decimals, dec_point, thousands_point) {
  if (number == null || !isFinite(number)) {
    // throw new TypeError("number is not valid");
    return;
  }

  if (!decimals) {
    var len = number.toString().split(".").length;
    decimals = len > 1 ? len : 0;
  }

  if (!dec_point) {
    dec_point = ".";
  }

  if (!thousands_point) {
    thousands_point = ",";
  }

  number = parseFloat(number).toFixed(decimals);

  number = number.replace(".", dec_point);

  var splitNum = number.split(dec_point);
  splitNum[0] = splitNum[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousands_point);
  number = splitNum.join(dec_point);

  return number;
}

function number_unformat(value) {
  if (typeof value === "undefined") return 0;
  return parseFloat(value.replace(/,/g, "")) || 0;
}

// Sum all of element's parents' and its siblings' margin and border
// which then will be used to calculate top position of highlight modal
function calculate_empty_space(element, is_dialog = false) {
  var empty_space = 0;

  element.parents().each(function (idx1, el1) {
    empty_space +=
      parseFloat($(el1).css("margin-top")) +
      parseFloat($(el1).css("border-top-width"));

    // for detail modal inside div.ui-dialog, only need to calculate relative to ui-dialog
    if (is_dialog && $(el1).hasClass("ui-dialog")) return false;

    $(el1)
      .siblings()
      .each(function (idx2, el2) {
        empty_space +=
          parseFloat($(el2).css("margin-top")) +
          parseFloat($(el2).css("margin-bottom")) +
          parseFloat($(el2).css("border-top-width")) +
          parseFloat($(el2).css("border-bottom-width"));
      });
  });

  return empty_space + (is_dialog ? $(".ui-dialog").offset().top : 0);
}

function bind_scroll_event(element) {
  var collection = document.getElementsByClassName(element);

  for (var i = 0; i < collection.length; i++) {
    collection[i].addEventListener(
      "scroll",
      function () {
        $("#highlight-modal").removeClass("active");
        $(".simap_row-selected").removeClass("simap_row-selected");
      },
      true
    );
  }
}

$(document).on("click", "#highlight-modal #close-dialog", function () {
  $("#highlight-modal").removeClass("active");
  $(".simap_row-selected").removeClass("simap_row-selected");
});
