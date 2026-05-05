document.addEventListener('DOMContentLoaded', function () {

    // ===== CUSTOM FOOTER =====
    const footer = document.createElement('div');
    footer.className = 'custom-footer';

    footer.innerHTML = `
        © 2026 Invoice SaaS - Designed by Muhammad Rehan
    `;

    document.body.appendChild(footer);


    // ===== HEADER USER BADGE =====
    const header = document.querySelector('header[dusk="header"]');

    if (header) {
        const badge = document.createElement('div');

        badge.innerHTML = `
            <span style="
                background:#1e293b;
                padding:6px 10px;
                border-radius:8px;
                font-size:12px;
                margin-left:15px;
                color:#94a3b8;
            ">
                ${window?.Nova?.config?.user?.name ?? 'User'}
            </span>
        `;

        header.appendChild(badge);
    }

});