let subscribers = [];

let isInitialRoute = ( routeName ) => {
    let template = document.querySelector( '.js-router-template' );
    return template && routeName === template.getAttribute( 'data-template-name' );
};

/**
 * @param {string} route required
 * @param {Object} template required
 */
let subscribe = ( route: string, template: string ) => {

    subscribers[ route ] = template;
    if ( isInitialRoute( route ) ) {
        notify( route );
    }
};

let notify = ( route ) => {
    if ( route && subscribers[ route ] ) {
        require( "../" + subscribers[ route ] ).onStart();
    }
};

export{ subscribe };