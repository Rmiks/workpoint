import Dom from "../helpers/dom";
import Env from "../helpers/env";
import * as $ from "jquery";

class _Menu {
    private $btn: DomInterface;
    private $header: DomInterface;
    private $items: DomInterface;

    constructor() {
        this.$btn    = Dom( ".js-menu-btn" );
        this.$header = Dom( ".pageHeader" );
        this.$items  = Dom( ".js-menu-scroll-link" );
    }

    watch() {
        this.$btn.on( 'click', this.toggleMenu );
        let that = this;
        this.$items.on( "click", function ( e: Event ) {
            e.preventDefault();
            if ( Env.isSimple() ) {
                that.closeMenu();
            }
            that.scrollToSection.apply( this, [ e, that.$header.get().scrollHeight ] );
        } );
    };

    scrollToSection( this: HTMLElement, e: Event, headerHeight ) {
        let $target;
        let href = this.getAttribute( "href" );
        if ( Env.isSimple() && href === "#subscriptions" ) {
            $target = $( ".js-subscriptions-mobile-target" );
        }
        else {
            $target = $( href );
        }
        if ( $target.length ) {
            let targetTop = $( $target.get() ).offset().top;
            console.log( targetTop );
            // let scrollTopValue = Env.isSimple() ? targetTop : targetTop - headerHeight;
            let scrollTopValue = Env.isSimple() && href === "#subscriptions" ? targetTop - (headerHeight * 2) : targetTop - headerHeight;
            $( "body, html" )
                .stop()
                .animate( {
                        scrollTop: scrollTopValue
                    },
                    '500', 'swing' );
        }
    }

    toggleMenu = () => {
        if ( Env.isSimple() ) {
            if ( !this.$header.hasClass( 'is-open' ) ) {
                this.openMenu();
            }
            else {
                this.closeMenu();
            }
        }
    };


    openMenu = () => {
        this.$header.addClass( 'is-opening is-open' );
    };


    closeMenu = () => {
        this.$header.removeClass( 'is-opening is-open' );
        this.$header.addClass( 'is-closing' );

        setTimeout( () => {
            this.$header.removeClass( 'is-closing' );
        }, 300 );
    }
}

export let Menu = new _Menu();