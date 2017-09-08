/**
 *
 * General purpose Dom operations helper.
 * Usage:
 *
 * import Dom from "helpers/dom";
 * let $element = Dom(".class"). // if multiple matches are found, all of them are selected
 *
 * Some methods can be chained:
 * $element.removeClass(".class").empty();
 *
 * To get the underlying dom node:
 * let element = $element.get();
 *
 * Workaround to let typescript know that you are getting an array of dom nodes:
 * let element = $element.getArray();
 *
 * @param _selector string | HTMLElement
 * @returns DomInterface
 * @constructor
 */
function Dom( _selector: string | Element ): DomInterface {
    let $element;
    if ( typeof _selector === "string" ) {
        $element = [].slice.call( document.querySelectorAll( _selector ) );
        if ( $element.length === 1 ) {
            $element = $element[ 0 ];
        }
        else if ( !$element.length ) {
            $element = null;
        }
    }
    else if ( typeof _selector === "object" ) {
        $element = _selector;
    }
    /**
     * @class Dom
     */
    return {
        get(): HTMLElement {
            return $element;
        },
        getArray(): HTMLElement[] {
            return $element.constructor === Array ? $element : [ $element ];
        },
        remove() {
            $element && $element.parentNode && $element.parentNode.removeChild( $element );
        },
        empty() {
            while ( $element.firstChild ) {
                $element.removeChild( $element.firstChild );
            }
        },
        on( type, callback ) {
            if ( $element ) {
                if ( $element.constructor === Array ) {
                    $element.forEach( function ( $item ) {
                        $item.addEventListener( type, callback );
                    }, this );
                }
                else {
                    $element.addEventListener( type, callback );
                }
            }
            return this;
        },
        off( type, callback ) {
            if ( $element ) {
                $element.removeEventListener( type, callback );
            }
            return this;
        },
        hasClass( classToCheck ) {
            return $element && ( ' ' + $element.className + ' ' ).indexOf( ' ' + classToCheck + ' ' ) > -1;
        },
        addClass( classToAdd: string ) {
            if ( $element && !this.hasClass( classToAdd ) ) {
                $element.className = [ $element.className, classToAdd ].join( ' ' );
            }
            return this;
        },
        removeClass( classToRemove ) {
            if ( $element ) {
                if ( $element.constructor === Array ) {
                    $element.forEach( function ( $item ) {
                        $item.className = $item.className.replace( classToRemove, '' );
                    }, this );
                }
                else {
                    $element.className = $element.className.replace( classToRemove, '' );
                }
            }
        },
        toggleClass( classToToggle ) {
            if ( $element ) {
                if ( this.hasClass( classToToggle ) ) {
                    this.removeClass( classToToggle );
                }
                else {
                    this.addClass( classToToggle );
                }
            }
        },
        find( selector ) {
            if ( typeof selector === 'string' ) {
                return Dom( $element.querySelector( selector ) );
            }
        },
        parent ( selector ) {
            let _element = $element;
            while ( _element = _element.parentNode ) {
                if ( typeof _element.matches !== "undefined" && _element.matches( selector ) ) {
                    return Dom( _element );
                }
            }

            return Dom( _element );
        },
        css( properties ) {
            if ( !$element ) {
                return false;
            }
            if ( $element.constructor === Array ) {
                $element.forEach( function ( $item ) {
                    Object.keys( properties ).forEach( function ( element, key ) {
                        $item.style[ element ] = properties[ element ];
                    } );
                }, this );
            }
            else {
                Object.keys( properties ).forEach( function ( element, key ) {
                    $element.style[ element ] = properties[ element ];
                } );
            }
        },
        serialize() {
            let formData = {};
            [].slice.call( $element.elements ).forEach( ( item ) => {
                if ( item.name ) {
                    if ( typeof(item.type) !== "undefined" && item.type === 'checkbox' ) {
                        formData[ item.name ] = item.checked ? 1 : 0;
                    } else {
                        formData[ item.name ] = item.value;
                    }
                }
            } );
            return formData;
        },

        isInView() {
            const rect = $element.getBoundingClientRect();

            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        }
    };
}
export default Dom;