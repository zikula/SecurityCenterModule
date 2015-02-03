

<div class='pull-right clearfix'><i class='fa fa-ambulance fa-lg text-danger'></i> <a data-toggle="modal" data-target="#help-box">{gt text="Help"}</a></div>


<!-- Modal -->
<div class="modal fade" id="help-box" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Block "Tree-like menu</h4>
            </div>
            <div class="modal-body">
                {gt text='Block "Tree-like menu (menutree)" allows you to create the extended menu with nested structure. Below you will find some basic information.'}

                <h5>{gt text="How to start?"}</h5>

                <p>
                    {gt text='To <strong>add the first item</strong> to the menu, click link "<strong>Add</strong>". You will get a form in which you must enter the name of the item and possibly other parameters (title, address, css class). At the top of the form you can find drop-down list with installed languages, which allows to switch between languages.'}
                </p>

                <p>
                    {gt text='While adding a new block, you can <strong>import</strong> an existing menu (supported blocks: "menu", "extmenu", "menutree" and "dynamenu"). To this purpose choose a block from the drop-down list.'}
                </p>

                <h5>{gt text='The basic options'}</h5>

                <p>
                    {gt text='To <strong>move</strong> an item, simply <strong>grab a folder icon</strong> and drop it on desired position.'}
                </p>

                <p>
                    {gt text='<strong>Click the name</strong> of an item, to view a <strong>context menu</strong>, which includes the following options:'}
                </p>

                <ul>
                    <li>{gt text='<strong>Edit</strong>: opens the edit form'}</li>
                    <li>{gt text='<strong>Delete</strong>: removes the selected element and all its subelements'}</li>
                    <li>
                        {gt text='<strong>Add new...</strong>'}
                        <ul>
                            <li>{gt text='<strong>before</strong>: opens a create form and adds the new item before the selected element'}</li>
                            <li>{gt text='<strong>after</strong>: opens a create form and adds the new item after the selected element'}</li>
                            <li>{gt text='<strong>as child</strong>: opens a create form and adds the new item as a child of the selected element'}</li>
                        </ul>
                    </li>
                    <li>{gt text='<strong>Expand/Collapse this node</strong>: expands or collapses the selected node'}</li>
                    <li>{gt text='<strong>Deactivate/Activate</strong>: turns on or off the selected element (and all its subitems) for the current language'}</li>
                    <li>
                        {gt text='<strong>State</strong>'}
                        <ul>
                            <li>{gt text='<strong>Activate for all languages</strong>: turns on the selected element (and all its subitems) for all the languages'}</li>
                            <li>{gt text='<strong>Deactivate for all languages</strong>: turns off the selected element (and all its subitems) for all the languages'}</li>
                        </ul>
                    </li>
                </ul>

                <h5>{gt text='Multilingual options'}</h5>

                <p>
                    {gt text='If the site has many languages installed - the menu will include all language versions. Each menu element can a have separated name and title for each language. The URL address and css class may (but not need) be common for all languages.'}
                </p>

                <p>
                    {gt text='The list of available languages is displayed above the menu (current language is marked). By clicking any language name you can change the displayed language.'}
                </p>

               <h5>{gt text='Dynamic elements'}</h5>

                <p>
                    {gt text='Dynamic elements are menutree plugins that allows the use of automatically generated list in the menu. It may be a list of all modules, the list of categories, etc.'}
                </p>

                <p>
                    {gt text='Dynamic elements are inserted in the same way as any other element of the menu. The only difference is that the url must use proper syntax, which looks like: (ext:Modulename:PluginName:AdditionalParams). The list generated by the plugin replaces the original menu item.'}
                </p>

                <h6>{gt text='Adminlinks'}</h6>

                <p>
                    {gt text='This plugin generates a list of admin modules - similar to adminnav block.'}
                </p>

                <code>
                    {literal}{ext:ZikulaBlocksModule:adminlinks[:[flat,category]]}{/literal}
                </code>

                <ul>
                    <li>
                        {gt text='Last parameter is optional. It can be flat and/or category separated by a comma.'}
                        <ul>
                            <li>{gt text='<strong>flat</strong> - will add the admin links in the current menu. Without flat the links are grouped one level down.'}</li>
                            <li>{gt text='<strong>category</strong> - additionally groups the admin links by their category.'}</li>
                        </ul>
                    </li>
                    <li>{gt text='You can combine <strong>flat</strong> and <strong>category</strong> to have the category links added in the current menu.'}</li>
                </ul>

                <h6>{gt text='Content pages'}</h6>

                <p>
                    {gt text='Plugin for Content module. This plugin generates a list of Content pages.'}
                </p>

                <code>
                    {literal}{ext:ZikulaBlocksModule:Content:[groupby=page&parent=1]}{/literal}
                </code>

                <ul>
                    <li>
                        {gt text='Parameters in square brackets are optional:'}
                        <ul>

                            <li>{gt text='<strong>Groupby</strong> - allows to choose grouping method, allow values are menuitem (default) or page.'}</li>
                            <li>{gt text='<strong>Parent</strong> - id of parent node - this allows to get specified node of content pages.'}</li>
                        </ul>
                    </li>
                </ul>

                <p>
                    {gt text='Note that this plugin considers if pages are enabled for display in menu. So if you wonder why certain items do not appear, look if you enabled this option at corresponding Content pages.'}
                </p>

                <h6>{gt text='Modules list'}</h6>

                <p>
                    {gt text='This plugin generates a list of installed modules - similar to adequate option in standard menu.'}
                </p>

                <code>
                    {literal}{ext:ZikulaBlocksModule:modules:[flat]}{/literal}
                </code>

                <p>
                    {gt text='Parameter <strong>flat</strong> is optional and allows you to disable links grouping.'}
                </p>

               <h6>{gt text='News module'}</h6>

               <p>
                    {gt text='Plugin for News module, generates a list of useful links to module and a list of categories.'}
               </p>

                <code>
                    {literal}{ext:ZikulaBlocksModule:news:[flat=BOOL&links=view,add,cat,arch|ALL]}{/literal}
                </code>

                <ul>
                    <li>{gt text='Parameters in square brackets are optional:'}</li>
                    <li>{gt text='<strong>Flat</strong> - true or false, default is false, the parameter allows you to disable links grouping'}</li>
                    <li>
                        {gt text='<strong>Links</strong> - a list of items to be displayed - default is all (ALL), available components are:'}
                        <ul>
                            <li>{gt text='<strong>View</strong> - link to main News view'}</li>
                            <li>{gt text='<strong>Add</strong> - link to Submit News form'}</li>
                            <li>{gt text='<strong>Cat</strong> - list of News categories'}</li>
                            <li>{gt text='<strong>Arch</strong> - link to archive'}</li>
                        </ul>
                    </li>
                    <li>{gt text='Links given in the <strong>links</strong> parameter are displayed in the order in which they are provided.'}</li>
                </ul>

                <h6>{gt text='Userlinks'}</h6>

                <p>
                    {gt text='This plugin generates basic links for users.'}
                </p>

                <p>
                    {gt text='For logged-in users: Your Account, Profile, Private messages, Logout.'}
                </p>

                <p>
                    {gt text='For not logged users: Your Account, Login, Register, Lost password.'}
                </p>

                <code>
                    {literal}{ext:ZikulaBlocksModule:userlinks:[flat]}{/literal}
                </code>

                <p>
                    {gt text='Parameter <strong>flat</strong> is optional and allows you to disable links grouping.'}
                </p>
            </div>
        </div>
    </div>
</div>