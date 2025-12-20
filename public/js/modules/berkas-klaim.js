$(document).ready(function () {
    $(document).on("click", ".btn-download-lk", function () {
        const btn = $(this);
        const id = btn.data("id");

        const icon = btn.find(".icon");
        const text = btn.find(".text");

        btn.prop("disabled", true);

        icon.text("autorenew").addClass("spin");

        text.text("Loading...");

        const url = `/berkas-klaim/${id}/download-lembar-kontrol`;

        const a = document.createElement("a");
        a.href = url;
        a.style.display = "none";
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);

        setTimeout(() => {
            btn.prop("disabled", false);
            icon.text("picture_as_pdf").removeClass("spin");
            text.text("LK");
        }, 1500);
    });

    $(document).on("click", ".btn-download-ttr", function () {
        const btn = $(this);
        const id = btn.data("id");

        const icon = btn.find(".icon");
        const text = btn.find(".text");

        btn.prop("disabled", true);

        icon.text("autorenew").addClass("spin");

        text.text("Loading...");

        const url = `/berkas-klaim/${id}/download-tanda-terima`;

        const a = document.createElement("a");
        a.href = url;
        a.style.display = "none";
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);

        setTimeout(() => {
            btn.prop("disabled", false);
            icon.text("picture_as_pdf").removeClass("spin");
            text.text("TTR");
        }, 1500);
    });
});
