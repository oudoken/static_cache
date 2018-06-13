# static_cache
An e107 cms plugin for generating static html files.
Usefull on low hosting resource and/or for high load site.
The plugin generate static html files for any page except those excluded in configuration.
Please be aware that page containing forms (ie: login, search, etc) may NOT work!
Use at Your risk.

The actual versione of e107 do not provide an e_output for plugins to capture output buffering at the end of the page (a pull commit has been made so maybe in the next version).

You must modify the files in e107 folders:

e107_handlers\plugin_class.php

e107_core\templates\footer_default.php

With  the files in to_e107/ of this repo.
