interface NodeRequire {
    ensure: ( paths: string[], callback: ( require: <T>( path: string ) => T ) => void, name?: string ) => void;
}

interface STORE {
    styleHash: string;
    fontsHash: string;
    jsHash: string;
    stylesLoaded: boolean;
}

interface  DomInterface {
    get(): HTMLElement;
    getArray(): HTMLElement[];
    remove(): void;
    empty(): void;
    on( type: string, callback: ( this: any, e: Event ) => void ): void;
    off( type: string, callback: ( this: any, e: Event ) => void ): void;
    hasClass( classToCheck: string ): void;
    addClass( classToAdd: string ): void;
    removeClass( classToRemove: string ): void;
    toggleClass( classToToggle: string ): void;
    css( properties: Object ): void;
    serialize(): Object;
    find( selector: string ): DomInterface;
    parent( selector: string ): DomInterface;
    isInView(): boolean;
}

declare let STORE: STORE;
declare let Validation;