/**
 * Don't forget to install jquery if you need this module, eg:
 * yarn add @types/jquery jquery
 *
 * After installation:
 *
 * 1. Uncomment jquery import statement (line 12)
 *
 * 2. Comment away typescript definition shims (lines 14 - 27)
 */

// import * as $ from "jquery";

declare let $: any;
namespace JQuery {
    export interface NameValuePair {
        [x: string]: any
    }
}

interface JQuery {
    [x: string]: any
}

interface JQueryEventConstructor {
    [x: string]: any
}

export default class Validation {

    private form: JQuery;

    private clickedButton: JQuery = null;

    private submitOnOk = true;
    private ui         = true;
    private firstFocusCalled: boolean;
    private focus: boolean;

    constructor( nodeOrSelector, params ) {
        Validation.checkDependencies();

        if ( !(nodeOrSelector instanceof $) ) {
            this.form = $( nodeOrSelector );
        }
        else {
            this.form = nodeOrSelector;
        }

        if ( this.form.length > 1 ) {
            this.form = this.form.first();
            console.log( 'Multiple forms are not supported for single validation script instance.' );
        }

        if ( typeof params == 'object' ) {
            if ( params.submitOnOk === false ) {
                console.log( 'submitOnOk is deprecated. Use event.preventDefault() in event handlers to prevent autosubmit.' );
                this.submitOnOk = false;
            }

            if ( params.ui == false ) {
                this.ui = false;
            }
        }

        /* attach events */
        this.form.on( 'click', 'input[type="submit"], input[type="image"], button', ( event: JQueryEventConstructor ) => {

                let target = $( event.target );

                // register only submit buttons - buttons with type="submit" or without type attribute at all
                // direct target[0].type property is used because of inconsistent attr() method return values
                // between older and newer jQuery versions
                if ( target.is( 'button' ) && (<HTMLButtonElement>target[ 0 ]).type !== 'submit' ) {
                    return;
                }
                this.clickedButton = target;
            }
        );

        // submit
        this.form.submit(
            ( event ) => {
                event
                    .preventDefault();

                this
                    .validateForm();
            }
        )
        ;


        $( document ).on( "ok error fail", this.onValidation );
    }

    onValidation = ( event, targetValidation?: Validation, error?: { [x: string]: any } ) => {
        if ( targetValidation !== this || event.isDefaultPrevented() || !this.form[ 0 ] ) {
            return;
        }

        switch ( event.type ) {
            case 'ok':      // validation passed
                if ( this.submitOnOk ) {
                    this.submitForm();
                }
                break;

            case 'error':   // validation error
                if ( this.ui ) {
                    alert( error.message );
                }
                this.clickedButton = null;
                break;

            case 'fail':  	// fail (internal validation failure, not a user error)

                this.submitForm();
                break;
        }
    };

    validateForm = () => {
        let that = this;

        let data  = this.form.serializeArray();
        let files = this.form.find( 'input[type=file]' );

        files.each( function() {
            data.push( <JQuery.NameValuePair>{ name: $( this ).attr( 'name' ), value: $( this ).val() } );
        } );

        if ( (this.clickedButton) && (this.clickedButton.length > 0) && this.clickedButton.attr( 'name' ) ) {
            data.push( <JQuery.NameValuePair>{
                name : this.clickedButton.attr( 'name' ),
                value: this.clickedButton.val()
            } );
        }
        data.push( { name: 'getValidationXml', value: "1" } );
        data.push( { name: 'validation[format]', value: 'json' } );

        $.ajax
         ( {
             url       : this.form.attr( 'action' ),
             type      : this.form.attr( 'method' ),
             data      : data,
             dataType  : 'json',
             cache     : false,
             converters: {
                 "* text"   : String,
                 "text json": $.parseJSON,
                 "text xml" : $.parseXML,
                 "xml json" : function( xml )   // convert old xml response to the new json format
                 {
                     let json = null;
                     if ( xml ) {
                         json = {};

                         xml         = $( xml );
                         json.status = xml.find( 'response' ).attr( 'status' );

                         let errors = [];
                         xml.find( 'response > error' ).each( function() {
                             let errorNode = $( this );
                             errors.push(
                                 {
                                     field  : errorNode.children( 'focus' ).text(),
                                     code   : errorNode.children( 'code' ).text(),
                                     message: errorNode.children( 'message' ).text()

                                 } );
                         } );

                         if ( errors.length > 0 ) {
                             json.errors = errors;
                         }
                     }
                     return json;
                 }
             },
             success   : function( response, textStatus, jqXHR ) {
                 let result = ((response) && (typeof response.status != 'undefined')) ? response.status : null;

                 let errors = [];

                 switch ( result ) {
                     case 'ok':
                         that.form.trigger( 'ok', [ that ] );
                         break;

                     case 'error':

                         if ( typeof response.errors != 'undefined' ) {
                             let keyMap =
                                     {
                                         'field': 'fieldName',
                                         'code' : 'errorCode'
                                     };

                             $.each( response.errors, function( index, error ) {
                                 let errObj = {};

                                 // copy all other values to error object
                                 for ( let key in error ) {
                                     let localKey       = (typeof keyMap[ key ] != 'undefined') ? keyMap[ key ] : key;
                                     errObj[ localKey ] = error[ key ];
                                 }

                                 errors.push( errObj );

                             } );
                         }
                         else {
                             errors.push(
                                 {
                                     message  : response.message,
                                     errorCode: response.errorCode,
                                     fieldName: response.errorFields[ 0 ].name
                                 } );
                         }

                         break;
                     default:

                         that.form.trigger( 'fail', [ that ] );

                         break;
                 }

                 that.firstFocusCalled = false;

                 $.each( errors, function( index, error ) {
                     let field = null;

                     let eventTarget = null;
                     that.focus      = (!that.firstFocusCalled); // focus only the first error field

                     if ( error.fieldName != '__form__' ) {
                         field = that.form.find( '*[name="' + error.fieldName + '"]' ).first();
                     }

                     if ( field && field.length > 0 ) {
                         eventTarget = field;
                     }
                     else {
                         eventTarget = that.form;
                         that.focus  = false;
                     }

                     eventTarget.trigger( 'beforeError', [ that, error ] );

                     if ( that.focus ) {
                         eventTarget.focus();

                         that.firstFocusCalled = true;
                     }
                     eventTarget.trigger( 'error', [ that, error ] );
                 } );
             },
             complete  : function( jqXHR, textStatus ) {
                 if ( textStatus !== 'success' ) {
                     that.form.trigger( 'fail', [ that ] );
                 }
             }
         } );
    };

    submitForm = () => {

        // append clicked button as a hidden field
        // because no button value will be sent when submitting the form via .submit()
        if ( (this.clickedButton) && (this.clickedButton.length > 0) && this.clickedButton.attr( 'name' ) ) {
            let input = $( '<input type="hidden" />' );
            input.attr( 'name', this.clickedButton.attr( 'name' ) );
            input.val( this.clickedButton.val() );
            input.appendTo( this.form );
        }
        (<HTMLFormElement>this.form[ 0 ]).submit();
    };

    static checkDependencies() {
        if ( $ === undefined ) {
            console.log( 'Validation requires jQuery.' );
        }
    };
}