<script type="text/javascript">
	$(document).on("submit", "#<?=$arParams["PARENT_ID"];?> form", function () {
		$(this).find(".sf_feedback_form_error").remove();

		if (!$(this).find('.user-agree-checkbox input[type=checkbox]').prop('checked')) {
			return false;
		}

		$.ajax({
			type: "POST",
			url: "<?=$arParams["PATH"];?>",
			data: $(this).serialize(),
			cache: false,
			async: false,
			success: function (html) {
				$("#<?=$arParams["PARENT_ID"];?>").html(html);
				setTimeout(function() {
					$("#<?=$arParams["PARENT_ID"];?> .input_error").removeClass("input_error");
				}, 1000);

				BX.UserConsent.loadFromForms();
			}
		});

		return false;
	});
</script>