//<<<< FILE MANAGEMENT FUNCTION >>>>
		function delete_file(f,after_funct) {
		  if (confirm("Are you sure you want to delete file '" + f + "' ?")) {
			var url = "./codejump.php?del_file=" + f; 
			jQuery.get(url, function(data) {
			  if (data == 1)
				alert("File '" + f + "' successfully deleted!");
              /*else
                alert("Error in deleting file '" + f + "'!\n" + data); */
			  location.reload();
			});
		  }
		  //run custom after_funct()
		  after_funct=after_funct || function() { } //if undefined, use default empty function
		  after_funct();
		}

		function rename_file(f1,after_funct) {
          var f2 = prompt('Enter the new file name you desire:');
		  if (confirm("Are you sure you want to rename file '" + f1 + "' to '" + f2 + "'?")) {
			var url = "./codejump.php?rename_from=" + f1 + "&rename_to=" + f2; 
			jQuery.get(url, function(data) {
			  if (data == 1) 
				alert("File '" + f1 + "' successfully renamed to '" + f2 + "'!");
              else
                alert("Error in renaming file '" + f1 + "'!\n" + data); 
			  location.reload();
			});
		  }
		  //run custom after_funct()
		  after_funct=after_funct || function() { } //if undefined, use default empty function
		  after_funct();
		}

		function create_new_file(after_funct) {
		  filename = prompt('Insert new name for the file');
		  var url = "./codejump.php?create_new=" + filename; 
		  jQuery.get(url, function(data) {
			location.reload();
		  });
		  //run custom after_funct()
		  after_funct=after_funct || function() { } //if undefined, use default empty function
		  after_funct();
		}
