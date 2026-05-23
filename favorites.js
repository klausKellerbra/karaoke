// favorites.js
// Gerencia favoritos de músicas usando cookie 'karaoke_favs'
(function(){
    function getCookie(name){
        const v = document.cookie.match('(^|;)\\s*'+name+'\\s*=\\s*([^;]+)');
        return v ? decodeURIComponent(v.pop()) : null;
    }

    function setCookie(name, value, days){
        let expires = '';
        if (days){
            const d = new Date();
            d.setTime(d.getTime() + (days*24*60*60*1000));
            expires = '; expires=' + d.toUTCString();
        }
        document.cookie = name + '=' + encodeURIComponent(value) + expires + '; path=/';
    }

    function getFavorites(){
        const raw = getCookie('karaoke_favs');
        if (!raw) return [];
        try { return JSON.parse(raw); } catch(e){ return []; }
    }

    function saveFavorites(arr){
        setCookie('karaoke_favs', JSON.stringify(arr), 365);
    }

    function isFav(code){
        const f = getFavorites();
        return f.indexOf(String(code)) !== -1;
    }

    function toggleFav(code){
        let f = getFavorites();
        code = String(code);
        const idx = f.indexOf(code);
        let added = false;
        if (idx === -1){
            f.push(code);
            added = true;
        } else {
            f.splice(idx,1);
        }
        saveFavorites(f);
        updateButtons();
        return added;
    }

    function heartSVG(filled){
        if (filled){
            return '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 6.01 4.01 4 6.5 4c1.74 0 3.41.81 4.5 2.09C12.09 4.81 13.76 4 15.5 4 17.99 4 20 6.01 20 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>';
        }
        return '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.78 0L12 5.61l-1.02-1.01a5.5 5.5 0 0 0-7.78 7.78L12 21.35l8.8-8.0a5.5 5.5 0 0 0 .0-7.75z"></path></svg>';
    }

    function updateButtons(){
        const btns = document.querySelectorAll('.fav-btn');
        btns.forEach(b => {
            const code = b.getAttribute('data-codigo');
            const pressed = isFav(code);
            if (pressed){
                b.classList.add('fav');
                b.setAttribute('aria-pressed', 'true');
                b.innerHTML = heartSVG(true);
            } else {
                b.classList.remove('fav');
                b.setAttribute('aria-pressed', 'false');
                b.innerHTML = heartSVG(false);
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function(){
        document.body.addEventListener('click', function(e){
            const btn = e.target.closest('.fav-btn');
            if (!btn) return;
            // evita abrir o parent onclick
            e.stopPropagation();
            const code = btn.getAttribute('data-codigo');
            toggleFav(code);
        });

        updateButtons();
    });

    // support removal buttons on favorites page which have aria-pressed=true
    document.addEventListener('click', function(e){
        const rb = e.target.closest('.fav-btn');
        if (!rb) return;
        // if this button was rendered as a 'remove' (aria-pressed true), remove DOM after toggle
        const code = rb.getAttribute('data-codigo');
        if (rb.getAttribute('data-removed') === 'true') return;
        // small delay to allow cookie update
        setTimeout(function(){
            const el = document.getElementById('fav-' + code);
            if (el) el.remove();
        }, 50);
    });

    function clearAllFavorites(){
        saveFavorites([]);
        updateButtons();
    }

    window.KaraokeFavorites = Object.assign(window.KaraokeFavorites || {}, {
        clearAll: clearAllFavorites
    });

    // export for console/testing
    window.KaraokeFavorites = {
        get: getFavorites,
        isFav: isFav,
        toggle: toggleFav
    };
})();
