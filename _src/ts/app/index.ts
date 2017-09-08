import { subscribe } from "./modules/router";
import { Menu } from "./modules/menu";

Menu.watch();

subscribe( "language_root", "templates/language_root" );
// subscribe( "contacts", "templates/contacts" );