//select js
$(document).ready(function () {
    // Get dynamic modal ID
    $(".modal").each(function () {
        var modalId = $(this).attr("id");
        $(this)
            .find(".select2")
            .select2({
                dropdownParent: $(this).find(".modal-content"),
            });
    });

    $(".modal").each(function () {
        var modalId = $(this).attr("id");
        $(this)
            .find(".select2-multiple")
            .select2({
                dropdownParent: $(this).find(".modal-content"),
            });
    });
});

$(function () {
    $(".select-1").select2();
});

$(function () {
    $(".select-example").select2();
});
$(function () {
    $(".select-basic-multiple-four").select2();
});
$(".select-example-rtl").select2({
    dir: "rtl",
});
$(".js-example-disabled").select2();
$(".select-basic-multiple-five").select2();
$(".select-basic-multiple-seven").on("click", function () {
    $(".js-example-disabled").prop("disabled", false);
    $(".select-basic-multiple-five").prop("disabled", false);
});
$(".select-basic-multiple-six").on("click", function () {
    $(".js-example-disabled").prop("disabled", true);
    $(".select-basic-multiple-five").prop("disabled", true);
});
$(".select2-icon").select2({
    width: "100%",
});
$(".select2-icons").select2({
    width: "100%",
});
