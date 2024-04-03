# TTv3-Simple-File-Uploader

No SQL edits or entries. Feel free to alter or modify this in any way you want

Its useful for sending files to friends and thats what I made it for, but you can do whatever you want.

This is a simple file uploader for your TorrentTrader to use however you see fit. Share files by uploading them directly to your server
rather than using a file sharing service. If you know what you are doing, you can limit its use to select people, but thats up to you.

This is a basic code to start with and only allows users above level 6 to delete files. Access to the page is set in the config.

Since this does not use any database tables, the uploader username is prepended to the filename, so its easy to see who added the file, even after you download it.

Make sure your php.ini is set to allow file uploads, and that you dont set the allowed upload size in this mod to exceed upload_max_filesize and post_max_size settings. (post_max_size should be a little higher than upload_max_filesize for overhead)
