Jump To Menu Block
------------------

This block has been provided by Tim Williams (tmw@autotrain.org) at AutoTrain.

This block will provide a 'jump to' menu within Moodle 2.x, similar to that used by Moodle 1.x (much of the code
has been borrowed from 1.x). Install the block as normal by copying the contents of this download
into the moodle/blocks directory.

Once installed, the block can operate in a number of different ways :

1) By default, the block will use some clever javascript to display the jump to menu below the login/out link,
while hiding the actual block on the page when not in editing mode. If you place the block on a part of the page
with no other blocks, you will probably find that you get an empty column. Users without javascript will see the
menu inside a normal Moodle block.
2) If you prefer to have the menu displayed inside a normal Moodle block, then change the global config setting.
3) If you want the menu to appear on content pages which can't display blocks (eg framed HTML), or would prefer
not to use javascript to place the menu, then you can patch the layout templates in your chosen theme so that 
the menu is displayed on all pages. Just add the following lines:

            require_once($CFG->dirroot."/blocks/jumpto_menu/lib.php");
            echo block_jumpto_menu_html();

at the point where you would like the menu to be displayed. We recommend that these lines be placed at the end
of the "headermenu" div, immediately after the line "echo $PAGE->headingmenu;". If you patch the themes, you
should switch off the "Show menu below login/logout link" option in the blocks global config. You may want to
disable use of the Jump To block altogether.