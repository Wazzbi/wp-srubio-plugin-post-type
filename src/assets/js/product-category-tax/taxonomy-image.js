jQuery(document).ready(function ($) {
  // The upload button action
  $(".upload_image_button").on("click", function (e) {
    e.preventDefault();

    var button = $(this);
    var custom_uploader = wp
      .media({
        title: "Select Image",
        button: {
          text: "Use this image",
        },
        multiple: false,
      })
      .on("select", function () {
        var attachment = custom_uploader
          .state()
          .get("selection")
          .first()
          .toJSON();
        $("#term_image_id").val(attachment.id);
        $("#term_image_preview").html(
          '<img src="' +
            attachment.url +
            '" style="max-width:150px; height:auto;">'
        );
        button.next(".remove_image_button").show();
      })
      .open();
  });

  // The remove button action
  $(".remove_image_button").on("click", function (e) {
    e.preventDefault();
    var button = $(this);
    $("#term_image_id").val("");
    $("#term_image_preview").html("");
    button.hide();
  });
});
