$(".custom-file-input").on("change", function () {
    let filename = $(this).val().split("\\").pop();
    $(this).next(".custom-file-label").addClass("selected").html(filename);
});

$('[data-toggle="tooltip"]').tooltip();

$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

function preview(target, image) {
    $(target).attr("src", window.URL.createObjectURL(image)).show();
}

function resetForm(selector) {
    $(selector)[0].reset();

    if ($(selector).find(".summernote").length !== 0) {
        $(selector).find(".summernote").summernote("code", "");
    }

    if ($(selector).find(".img-thumbnail").length !== 0) {
        $(selector).find(".img-thumbnail").attr("src", "").hide();
        $(selector).find(".custom-file-label").text("Choose file");
    }

    if ($(selector).find(".select2").length !== 0) {
        $(selector).find(".select2").val(null).trigger("change");
    }

    if ($(selector).find(".duallistbox").length !== 0) {
        $(selector).find(".duallistbox").empty();
        $(selector).find(".duallistbox").bootstrapDualListbox("refresh");
    }

    $(".custom-file-input").next(".custom-file-label").text("Choose file");
    $(".custom-file-input").val("");

    $(".select2").trigger("change");
    $(".select2").val("").trigger("change");

    $(
        ".form-control, .custom-select, [type=radio], [type=checkbox], [type=file], .select2, .note-editor"
    ).removeClass("is-invalid");
    $(".invalid-feedback").remove();
}

function loopForm1(originalForm) {
    for (field in originalForm) {
        if ($(`[name=${field}]`).attr("type") != "file") {
            if ($(`[name=${field}]`).hasClass("summernote")) {
                $(`[name=${field}]`).summernote("code", originalForm[field]);
            } else if ($(`[name=${field}]`).attr("type") == "checkbox") {
                $(`[name=${field}]`)
                    .filter(`[value="${originalForm[field]}"]`)
                    .prop("checked", true);
            } else if ($(`[name=${field}]`).attr("type") == "radio") {
                $(`[name=${field}]`)
                    .filter(`[value="${originalForm[field]}"]`)
                    .prop("checked", true);
            } else {
                $(`[name=${field}]`).val(originalForm[field]);
            }

            $("select").trigger("change");
        } else {
            $(`.preview-${field}`).attr("src", originalForm[field]).show();
        }
    }
}

function loopForm(originalForm) {
    for (const field in originalForm) {
        const $fieldElement = $(`[name=${field}]`);
        const fieldType = $fieldElement.attr("type");

        if (fieldType !== "file") {
            if ($fieldElement.hasClass("summernote")) {
                $fieldElement.summernote("code", originalForm[field]);
            } else if (fieldType === "checkbox") {
                $fieldElement
                    .filter(`[value="${originalForm[field]}"]`)
                    .prop("checked", true);
            } else if (fieldType === "radio") {
                $fieldElement
                    .filter(`[value="${originalForm[field]}"]`)
                    .prop("checked", true);
            } else {
                $fieldElement.val(originalForm[field]);
            }

            if ($fieldElement.is("select")) {
                $fieldElement.trigger("change");
            }
        } else {
            $(`.preview-${field}`).attr("src", originalForm[field]).show();
        }
    }
}

function loopErrors(errors) {
    $(".invalid-feedback").remove();
    $(".is-invalid").removeClass("is-invalid");

    if (errors == undefined) {
        return;
    }

    for (error in errors) {
        let inputField = $(`[name=${error}]`);

        inputField.addClass("is-invalid");

        if (inputField.hasClass("select2")) {
            inputField.addClass("is-invalid");
            $(
                `<span class="error invalid-feedback">${errors[error][0]}</span>`
            ).insertAfter(inputField.next());
        } else if (inputField.hasClass("summernote")) {
            $(".note-editor").addClass("is-invalid");
            $(
                `<span class="error invalid-feedback">${errors[error][0]}</span>`
            ).insertAfter(inputField.next());
        } else if (inputField.hasClass("custom-control-input")) {
            $(
                `<span class="error invalid-feedback">${errors[error][0]}</span>`
            ).insertAfter(inputField.next());
        } else if (inputField.attr("type") === "radio") {
            // Menambahkan error di akhir grup radio
            $(
                `<span class="error invalid-feedback d-block">${errors[error][0]}</span>`
            ).insertAfter(inputField.last().parent());
        } else {
            if (inputField.length == 0) {
                let arrayField = $(`[name="${error}[]"]`);
                arrayField.addClass("is-invalid");
                $(
                    `<span class="error invalid-feedback">${errors[error][0]}</span>`
                ).insertAfter(arrayField.next());
            } else {
                if (
                    inputField.next().hasClass("input-group-append") ||
                    inputField.next().hasClass("input-group-prepend")
                ) {
                    $(
                        `<span class="error invalid-feedback">${errors[error][0]}</span>`
                    ).insertAfter(inputField.next());
                    $(".input-group-append .input-group-text").css(
                        "border-radius",
                        "0 .25rem .25rem 0"
                    );
                    $(".input-group-prepend")
                        .next()
                        .css("border-radius", "0 .25rem .25rem 0");
                } else {
                    $(
                        `<span class="error invalid-feedback">${errors[error][0]}</span>`
                    ).insertAfter(inputField);
                }
            }
        }
    }
}

function format_uang(input) {
    a = input.value;
    if (a == undefined) {
        a = input.toString();
    }
    b = a.replace(/[^\d]/g, "");
    c = "";
    length = b.length;

    j = 0;
    for (i = length; i > 0; i--) {
        j = j + 1;
        if (j % 3 == 1 && j != 1) {
            c = b.substr(i - 1, 1) + "." + c;
        } else {
            c = b.substr(i - 1, 1) + c;
        }
    }
    if (input.value == undefined) {
        return c;
    }

    input.value = c;
}

var url = window.location;

// for sidebar menu entirely but not cover treeview
$("ul.nav-sidebar a")
    .filter(function () {
        if (this.href) {
            return this.href == url || url.href.indexOf(this.href) == 0;
        }
    })
    .addClass("active");

// for the treeview
$("ul.nav-treeview a")
    .filter(function () {
        if (this.href) {
            return this.href == url || url.href.indexOf(this.href) == 0;
        }
    })
    .parentsUntil(".nav-sidebar > .nav-treeview")
    .addClass("menu-open")
    .prev("a")
    .addClass("active");
