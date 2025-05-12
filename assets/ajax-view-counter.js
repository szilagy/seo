document.addEventListener("DOMContentLoaded", function () {
    if (!document.cookie.includes("sdt_viewed=")) {
        fetch(sdt_ajax_view_counter.ajax_url, {
            method: "POST",
            credentials: "same-origin",
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: "action=sdt_count_view&post_id=" + sdt_ajax_view_counter.post_id
        }).then(() => {
            var expires = new Date(Date.now() + 60 * 60 * 1000).toUTCString();
            document.cookie = "sdt_viewed=1; expires=" + expires + "; path=/";
        });
    }
});
