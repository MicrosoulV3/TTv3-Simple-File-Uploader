# TTv3-Simple-File-Uploader
This is a simple file uploader for your TorrentTrader to use however you see fit. Share files by uploading them directly to your server
rather than using a file sharing service. If you know what you are doing, you can limit its use to select people, but thats up to you.

This is a base to start with and only allows users above level 6 to delete files, and is open for anyone on the site to use, so make sure you have it set the way you want.

Since this does not use any database tables, the uploader username is prepended to the filename, so its easy to see who added the file, even after you download it.

Make sure your php.ini is set to allow file uploads, and that you dont set the allowed upload size in this mod to exceed upload_max_filesize and post_max_size setting (those should both be the same number)

You could do something like this at the top of the file to limit access

if (!$CURUSER || $CURUSER["class"]<"3"){
    show_error_msg(T_("ERROR"), T_("SORRY_NO_RIGHTS_TO_ACCESS"), 1);
}
