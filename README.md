CodeJump version 0.8
====================

CodeJump is a web based code editor that enable you to code on your server directly from 
your browser, without having the need to upload or go through a series of servers control 
panel options. CodeJump previously named CodeMaster, but because the name CodeMaster already 
used elsewhere, I decided to change the name to CodeJump. I hope it won't violate anyone's 
rights.

Copyright 2013 by azophy (www.azophy.com).

This simple editor is released under GPL license (included in the packages) and utilizing 
many other OpenSource technologies, such as :

- CodeMirror(http://www.codemirror.net)
- Emmet (http://emmet.io)
- jqTree (http://mbraak.github.io). 
- Free Flat UI (http://designmodo.com/flat-free/)

Any critics, suggestion, or question just contact me via twitter @azophy. 

Installation Instruction:
=========================
extract all the file into the hosting's folder which you want to code there, make sure the files
permission is suitable (at least it has '7' for the owner, but for security you could set the 
overall to '766' 

Plan fo the future
==================

- file upload feature
- file loading animation
- tabbed multi-file interface
- save as feature
- shortcut for: find & replace, indent, change between tabs
- manage version through git

ChangeLog
=========
What's new on version 0.x
------------------------
- bugfixe on ajax filename access
- update on interface. CodeJump now use DesignModo's Free Flat UI!


What's new on version 0.8
------------------------
- Authentification, username & password storage still file based
- Edit on interface: more sophisticated then previous version
- TreeVew File Browser with more features: rename beside create & delete
- bugfix on file permision detection. Now there's more information when error occured.


Change Log from CodeMaster
==========================
What's new on version 0.62:
-----------------------------
- bug fix on PHP5 which automatically add slashes to every sent data

What's new on version 0.6:
-----------------------------
- file list now reach 1 level deep iterating
- improvement in design and look & feel
