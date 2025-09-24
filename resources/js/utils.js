function loading(event, button, isDeleteButton = false, question = '') {
    // cegah submit default dulu
    event.preventDefault();

    button.setAttribute("disabled", "true");
    button.classList.add("opacity-50", "cursor-not-allowed");

    if (!isDeleteButton) {
        // Tampilkan spinner lalu submit manual
        button.innerHTML = `
            <svg class="animate-spin h-5 w-5 mx-3 border-2 border-white border-t-transparent rounded-full" viewBox="0 0 24 24"></svg>
        `;
        button.form.submit();
    } else {
        // Konfirmasi delete
        if (confirm(question)) {
            button.form.submit(); // hanya submit kalau OK
        } else {
            // Batalkan delete → balikin state tombol
            button.removeAttribute("disabled");
            button.classList.remove("opacity-50", "cursor-not-allowed");
        }
    }

    return false; // selalu stop submit default
}

export { loading };
