/**
 * Don't forget to install jquery if you need this module, eg:
 * yarn add @types/jquery jquery
 *
 * After installation:
 * Uncomment import statements
 * Comment 'declare let $' lines after installation for enhanced typescript support
 */

// import * as $ from 'jquery';
// import Validation from "../modules/validation";

declare let $: any;

let onStart = () => {
    console.log( "we are in contacts" );
};

export { onStart };

contacts();

function contacts() {

    let $form = $( '.contacts form' );
    if ( $form.length === 0 ) {
        return;
    }

    //validate form
    new Validation( $form, { ui: false } );
    $form.on( 'error', function ( event, v, error ) {

        let $target = $( event.target );
        if ( $target.is( 'input[type!="hidden"],textarea,select' ) ) {
            $( '[for="' + $target.attr( 'name' ) + '"].hasError' ).text( error.message );
            $target.addClass( 'withError' );
        }

    } );

    function clearErrors( $form ) {

        $form.find( '.hasError' ).html( '' );
        $form.find( '.withError' ).removeClass( 'withError' );
    }

    $form.on( 'submit', function () {
        console.log( 'submit done' );
        clearErrors( $( this ) );
    } );

}
