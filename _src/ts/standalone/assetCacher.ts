/// <reference path="../definitions/globals.d.ts"/>

namespace assetCacher {

    const styleHashKey = "styleHash";
    const fontsHashKey = "fontsHash";

    let onWindowLoad = ( element: Window | Element, type: string, callback: EventListener ) => {
        element.addEventListener ? element.addEventListener( type, callback, !1 ) : element[ "attachEvent" ] && element[ "attachEvent" ]( "on" + type, callback )
    };

    let getStylesFromLocalStorage = ( key: string, path: string ) => {
        return localStorage && localStorage[ key + "_content" ] && localStorage[ key + "_file" ] === path
    };



    let addStyle = ( key: string, path: string, callback: Function ) => {
        if ( localStorage && isLocalStorageAvailable() && XMLHttpRequest ) getStylesFromLocalStorage( key, path ) ? insertStylesheet( localStorage[ key + "_content" ], callback, key ) : requestStyleFile( key, path, callback ); else {
            let link  = document.createElement( "link" );
            link.href = "/" + path;
            link.id   = key;
            link.rel  = "stylesheet";
            link.type = "text/css";
            document.getElementsByTagName( "head" )[ 0 ].appendChild( link );
            document.cookie = key;
            callback( key );
        }
    };


    let requestStyleFile = ( key: string, path: string, callback: Function ) => {
        let request = new XMLHttpRequest;
        request.open( "GET", path, !0 );
        request.onreadystatechange = function () {
            4 === request.readyState && 200 === request.status && (insertStylesheet( request.responseText, callback, key ), localStorage[ key + "_content" ] = request.responseText, localStorage[ key + "_file" ] = path)
        };
        request.send()
    };

    let insertStylesheet = ( styles, callback: Function, key: string ) => {
        let b = document.createElement( "style" );
        b.setAttribute( "type", "text/css" );
        document.getElementsByTagName( "head" )[ 0 ].appendChild( b );
        if ( typeof b[ "styleSheet" ] !== 'undefined' ) {
            b[ "styleSheet" ].cssText = styles;
        }
        else {
            b.innerHTML = styles;
        }
        callback( key );
    };

    let isLocalStorageAvailable = () => {
        let a = "leaf_test",
            b = sessionStorage;
        try {
            return b.setItem( a, "1" ), b.removeItem( a ), !0
        } catch ( a ) {
            return !1
        }
    };

    let loadLocalStorageCSS = ( key: string, path: string, callback: Function ) => {
        getStylesFromLocalStorage( key, path ) || document.cookie.indexOf( key ) > -1 ? addStyle( key, path, callback ) : onWindowLoad( window, "load", function () {
            addStyle( key, path, callback )
        } )
    };

    let onCssLoad = ( key ) => {
        if ( key === 'leaf_main' ) {
            STORE.stylesLoaded = true;
        }
    };

    if ( isLocalStorageAvailable() ) {
        if ( STORE.styleHash !== localStorage.getItem( styleHashKey ) ) {
            localStorage.setItem( styleHashKey, STORE.styleHash )
        }
        if ( STORE.fontsHash !== localStorage.getItem( fontsHashKey ) ) {
            localStorage.setItem( fontsHashKey, STORE.fontsHash )
        }
    }

    loadLocalStorageCSS( "leaf_webfonts", "styles/fonts." + STORE.fontsHash + ".css", onCssLoad );
    loadLocalStorageCSS( "leaf_main", "styles/style." + STORE.styleHash + ".css", onCssLoad )
}