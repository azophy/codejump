CodeJump Editor
====================

CodeJump is a web based code editor that enabe you to code on your server directly from the browser, 
without having the need to upload or go through a series of servers control panel options.

Essentially, CodeJump is a code editor + file browser. But CodeJump is more than that! CodeJump 
utilize [Emmet](http://emmet.io) for lightning-fast development. CodeJump also thrive to improve 
it’s interface so it would be as convinient (or even better!) as any other desktop based code editor. 
CodeJump is a heck of a tool for any web developer.

CodeJump previously named CodeMaster, but because the name CodeMaster already used elsewhere, I 
decided to change the name to CodeJump. I hope it won’t violate anyone’s rights.

This simple editor is released under GPL license (included in the packages) and utilizing 
many other OpenSource technologies, such as :

- CodeMirror(http://www.codemirror.net)
- Emmet (http://emmet.io)
- jqTree (http://mbraak.github.io). 
- Free Flat UI (http://designmodo.com/flat-free/)

Any critics, suggestion, or question just contact me via twitter @azophy. 

Why CodeJump?
-------------

**Free and OpenSource**

CodeJump is licensed under GPL, so you could use it freely without any need to pay me for the 
software. Just download, extract, and use it! Yeaaah!

**Direct Editing**

CodeJump offer a simple file browser interface and a to the point interface to directly edit 
and code the files on your server

**Ultra Fast coding**

CodeJump utilize emmet which enable you to code extremly fast. So if you type

	div.nav>ul.nav.list>(li>a[href=#]{Link #$})*5

in the editor section, it will magically transformed into:

	<div class="nav">
		<ul class="nav list">
			<li><a href="#">Link #1</a></li>
			<li><a href="#">Link #2</a></li>
			<li><a href="#">Link #3</a></li>
			<li><a href="#">Link #4</a></li>
			<li><a href="#">Link #5</a></li>
		</ul>
	</div>

For more information about the syntax, please read it here: http://docs.emmet.io/abbreviations/syntax/

**Easy File Management**

The file browser which can be accessed in the sidebar gave you complete freedom to easily 
manage your project directly from CodeJump page. You could create a new document, rename, 
and delete them easily. Freaking awesome!

Installation Instruction:
-------------------------
extract all the file into the hosting's folder which you want to code there, make sure the files
permission is suitable (at least it has '7' for the owner, but for security you could set the 
overall to '766' 

Plans for the future
-------------------
- file loading animation
- tabbed multi-file interface (still in consideration, is it really necessery or overkill)
- shortcut for: find & replace, indent, change between tabs
- manage version through git

ChangeLog
---------

### What's new on version 0.92
- doing some refactoring & reorganizing some files location
- add php highlighting
- modify file browser's look

### What's new on version 0.9
- file upload feature
- bugfixe on ajax filename access
- update on interface. CodeJump now use DesignModo's Free Flat UI!
- more serious treeview file browser! now it's able to recursively list your directory's contents!
- save as feature
- tested on Chrome 25.x

### What's new on version 0.8
- Authentification, username & password storage still file based
- Edit on interface: more sophisticated then previous version
- TreeVew File Browser with more features: rename beside create & delete
- bugfix on file permision detection. Now there's more information when error occured.


Change Log from CodeMaster
--------------------------

### What's new on version 0.62:
- bug fix on PHP5 which automatically add slashes to every sent data

### What's new on version 0.6:
- file list now reach 1 level deep iterating
- improvement in design and look & feel
