/// <reference types="google.analytics" />

import Dom from '../helpers/dom';

export let trackAll = () => {
    /* dom example: <a href="" class="_ga" data-ga-category="event category" data-ga-action="event action">click</a>  */
    if ( typeof ga === 'function' ) {
        Dom( '._ga' ).on( "click", onClick );
    }
};

function onClick( this: HTMLElement, e: Event ) {

    let category = this.getAttribute( 'data-ga-category' ) || 'untitled';
    let action   = this.getAttribute( 'data-ga-action' ) || 'untitled';
    let label    = this.getAttribute( 'data-ga-label' ) || null;

    trackSingle( category, action, label );
}

export function trackSingle( category?: string, action?: string, label?: string ) {
    let _category, _action, _label;

    _category = category || 'untitled';
    _action   = action || 'untitled';
    _label    = label || null;

    ga( 'send', 'event', _category, _action, _label );
}

export function pageView( props: any ) {
    if ( typeof ga === 'function' ) {
        ga( 'send', 'pageView', props );
    }
}
