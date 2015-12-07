<style type="text/css">
	#listContainer{
	  margin-top:15px;
	}

	#expList ul, li {
		list-style: none;
		margin:0;
		padding:0;
		cursor: pointer;
	}
	#expList p {
		margin:0;
		display:block;
	}
	#expList p:hover {
		background-color:#121212;
	}
	#expList li {
		line-height:140%;
		text-indent:0px;
		background-position: 1px 8px;
		padding-left: 20px;
		background-repeat: no-repeat;
	}

	/* Collapsed state for list element */
	#expList .collapsed {
		background-image: url(../img/collapsed.png);
	}
	/* Expanded state for list element
	/* NOTE: This class must be located UNDER the collapsed one */
	#expList .expanded {
		background-image: url(../img/expanded.png);
	}

	.listControl{
		margin-bottom: 15px;
	}
	.listControl a {
		border: 1px solid #555555;
		color: #555555;
		cursor: pointer;
		height: 1.5em;
		line-height: 1.5em;
		margin-right: 5px;
		padding: 4px 10px;
	}
	.listControl a:hover {
		background-color:#555555;
		color:#222222;
		font-weight:normal;
	}

	#listContainer SPAN, .blue{
		color: blue;
	}

	.red{
		color: red;
	}
	.white{
		color: white;
	}
	.green{
		color: green;
	}
	.yellow{
		color: #FFAF0A;
	}
</style>
<SCRIPT>
	function prepareList() {
      $('#expList').find('li:has(ul)')
      	.click( function(event) {
      		if (this == event.target) {
      			$(this).toggleClass('expanded');
      			$(this).children('ul').toggle('medium');
      		}
      		return false;
      	})
      	.addClass('collapsed')
      	.children('ul').hide();
      };

      $(document).ready( function() {
          prepareList();
      });

	function expand(ID){
		if(ID.indexOf("/") > -1){
			ID = ID.split("/");
			for(var I = 0; I < ID.length; I++){
				expand( ID[I] );
				if(I == ID.length-1){
					scrollIntoView( ID[I] );
				}
			}
		} else {
			element = document.getElementById(ID);
			if(!element.hasClass("expanded")) {
				element.click();
			}
		}
	}

	function scrollIntoView(eleID) {
		var e = document.getElementById(eleID);
		if (!!e && e.scrollIntoView) {
			e.scrollIntoView();
		}
	}

	function ExpandAll(){
		$('.collapsed').addClass('expanded');
		$('.collapsed').children().show('medium');
	}
	function CollapseAll(){
		$('.collapsed').removeClass('expanded');
		$('.collapsed').children().hide('medium');
	}
</SCRIPT>
<?php
	$settings = $Manager->get_settings();
	foreach(array("client", "profile", "document") as $Key){
		if(isset($_GET[$Key])){ $settings->$Key = $_GET[$Key];}
	}
	$language = "English";//only english is supported
	$strings = CacheTranslations($language, array("clients_%", "profiles_%", "orders_%", "tasks_%", "infoorder_%", "documents_%", "forms_%", "consent_withinborder"), $settings);
	$languages = implode(", ", languages());
	$IsSuper =  $Manager->read("super");

	$titles = getfields($Manager->enum_all("contents"));
	$ProfileTypes = getfields($Manager->enum_all("profile_types"));
	$ClientTypes  = getfields($Manager->enum_all("client_types"));
	$DocumentTypes  = getfields($Manager->enum_all("subdocuments"));
	$OrderTypes = getfields( $Manager->enum_all("product_types"), "Name");

	function getfields($Objects, $Field = "title"){
		$titles = array();
		foreach($Objects as $Object){
			if($Object->$Field) {$titles[] = $Object->$Field;}
		}
		return implode(", ", $titles);
	}
	$strings["REPLACEME"] = "REPLACE ME";
?>
<BR>Clicking a section of this page will expand it to show more information about it
<BR>What's visible will be dependant on your <?= $settings->profile; ?> type, the permissions of both you and the <?= $settings->client ?>(s) you are assigned to
<?php if($IsSuper) { echo '<BR>Since you are a super-user, you will have access to a lot more than regular users'; } ?>
<BR>These are the parts to the Veritas screen:

<TABLE WIDTH="200" STYLE="cursor: pointer;">
	<TR><TD BGCOLOR="#2D5F8B" ALIGN="CENTER" COLSPAN="2" ONCLICK="expand('theheader');" CLASS="white">Header
		<img alt="" class="img-circle" src="<?= $this->request->webroot; ?>img/profile/default.png" style="float: right; height: 18px;display: inline;" ONCLICK="expand('your-settings');">
	</TD></TR>
	<TR HEIGHT="100">
		<TD WIDTH="25%" ALIGN="CENTER" BGCOLOR="#4276A4" ONCLICK="expand('thesidebar');" CLASS="white">Sidebar</TD>
		<TD WIDTH="75%" ALIGN="CENTER" ONCLICK="expand('thecontent');">
			<TABLE WIDTH="75%" HEIGHT="10%"><TR><TD ALIGN="CENTER" BGCOLOR="#F7F7F7" STYLE="position: relative; top: -20px;" ONCLICK="expand('actionbar');"><i class="fa fa-home"></i> Action bar</TD></TR></TABLE>
			Content
			<TABLE WIDTH="75%" HEIGHT="10%"><TR><TD ALIGN="CENTER" BGCOLOR="#F5F5F5" STYLE="position: relative; bottom: -20px;" ONCLICK="expand('paginationbar');">Pagination bar</TD></TR></TABLE>
		</TD>
	</TR>
	<TR><TD BGCOLOR="#2D5F8B" ALIGN="CENTER" COLSPAN="2" ONCLICK="expand('thefooter');" CLASS="white">Footer</TD></TR>
</TABLE>

<div id="listContainer">
  <ul id="expList">
	<li id="theheader">The header
		<ul>
			<li>The right side contains a dropdown menu allowing you to access <SPAN ONCLICK="expand('theheader/your-settings');"><?= $strings["dashboard_mysettings"]; ?></SPAN>, switch languages, and logout</li>
			<LI ID="your-settings"><?= $strings["dashboard_mysettings"]; ?>
				<UL>
					<LI ONCLICK="expand('thesidebar/profiles/profile/profile-info');" CLASS="blue"><?= $settings->profile ?></LI>
					<LI ONCLICK="expand('thesidebar/profiles/profile/permissions');" CLASS="blue">Permissions</LI>
				</UL>
			</LI>
			<?php if($IsSuper){ ?>
				<LI>De-possess
					<UL><LI>If you have possessed a <?= $settings->profile ?>, click this to log back in as yourself</LI></UL>
				</LI>
			<?php } ?>
		</ul>
	</li>

	<LI id="thefooter">The footer
		<ul>
			<li>Some pages will show a list of checkboxes on the left side to indicate what permissions they use (visible when you hover your mouse over the checkbox) and if you have them enabled</li>
			<LI>Total Time is how long the page took to load</LI>
			<li>On the right side is a list of links to various pages (<?= $titles; ?>) which can be customized in the (system) settings page</li>
			<?php if($IsSuper) { ?>
				<li><?= $strings["dashboard_debug"]; ?> (On/Off)
					<UL>
						<LI>When <?= $strings["dashboard_debug"]; ?> is on, more information is shown about errors, and which files are being used</LI>
						<LI>Unless you are trying to track down a problem, or are developing, this should be turned off as it shows information that could be useful to hackers</LI>
					</UL>
				</LI>
				<LI id="system-settings">(System) Settings
					<UL>
						<LI>Logos
							<UL>
								<LI>You can set Primary (the top of the sidebar), Secondary (bottom of the sidebar), Login (for the login screen), and Client (shown for clients that don't set their own logo) logos.</LI>
								<LI>Clicking an image will select it, click "<?= $strings["forms_savechanges"]; ?>" to apply the change</LI>
								<LI>Click "Add new logo" or "<?= $strings["clients_addeditimage"]; ?>" to upload an image</LI>
								<LI>Changes to the <?= $settings->client; ?> logo will be applied without needing to click "<?= $strings["forms_savechanges"]; ?>" </LI>
							</UL>
						</LI>
						<LI id="pages">Pages
							<UL>
								<LI>You can customize the titles and descriptions for the <?= $titles; ?> pages for each language here</LI>
								<LI>Click "<?= $strings["forms_savechanges"]; ?>" to apply the change</LI>
							</UL>
						</LI>
						<LI>Display
							<UL>
								<LI>Edit how the words: <?= $settings->client . ', ' . $settings->profile  . ', ' . $settings->document; ?> for each language appear throughout the page as well as the site name (<?= $settings->mee; ?>)</LI>
								<LI>Click "<?= $strings["forms_savechanges"]; ?>" to apply the change</LI>
							</UL>
						</LI>
						<LI>Packages
							<UL>
								<LI>When you place an order, there is also a list of packages to choose from which determines which documents you have to fill out</LI>
								<LI>This page lets you choose which documents are assigned to each package, or if the package is visible at all</LI>
								<LI>On th eleft side is a list of packages, and a checkbox (EN) to indicate if it is visible</LI>
								<LI>Clicking a package lets you Rename it, Clear (remove all documents from it) or Delete it</LI>
								<LI>The text boxes at the bottom let you add a new package, clicking "Add" saves the change</LI>
								<LI>The right side lets you select which documents are assigned to the package based on what province the <?= $settings->profile; ?> driver's license was issued in</LI>
								<LI>ALL would make the documents apply to all driver's license provinces</LI>
								<LI>All Documents would show all of the documents for that province</LI>
								<LI>Changes to the documents are saved as you make them</LI>
							</UL>
						</LI>
						<LI>Configuration</LI>
							<UL>
								<LI>You can add, rename or enable/disable these:</LI>
								<LI><?= $settings->profile; ?> types
									<UL>
										<LI>Can Order
											<UL><LI>Sets whether or not orders can be placed for this <?= $settings->profile; ?> type</LI></UL>
										</LI>
									</UL>
								</LI>
								<LI><?= $settings->client; ?> types</LI>
								<LI><?= $settings->document; ?>s
									<UL>
										<LI>The name of this <?= $settings->document; ?> type in each language</LI>
										<LI>Color
											<UL><LI>What color this <?= $settings->document; ?> type shows up as in the <SPAN ONCLICK="expand('documents/listdocuments');"><?= $strings["index_listdocuments"]; ?></SPAN> page</LI></UL>
										</LI>
										<LI>Icon/Product
											<UL><LI>These were used to set how the top block would appear, but are no longer used</LI></UL>
										</LI>
										<LI><?= $strings["dashboard_delete"]; ?>
											<UL><LI>Delete this <?= $settings->document; ?> type</LI></UL>
										</LI>
									</UL>
								</LI>
								<LI>Click "Edit" to let you rename them, then "Save" to apply the changes</LI>
							</UL>
						<LI id="clear-data">Clear Data
							<UL>
								<LI>Clear Data
									<UL><LI>Systematically erase most data from the database, such as all <?= $settings->profile; ?>s except Supers, all orders/<?= $settings->document; ?>s, attachments</LI></UL>
								</LI>
								<LI>Scramble Data
									<UL><LI>Replaces private information (addresses, phone numbers, email addresses) with fake/garbage data</LI></UL>
								</LI>
								<LI id="clear-cache">Clear Cache
									<UL><LI>A way to clear CakePHP's (the framework this site is built off of) cache, in case a new language or column in a database was added</LI></UL>
								</LI>
							</UL>
						</LI>
						<LI>All Crons
							<UL>
								<LI>Run the CRON
									<UL>
										<LI>Triggers the CRON events</LI>
									</UL>
								</LI>
								<LI><?= $settings->client; ?>s
									<UL>
										<LI>ID</LI>
										<LI>Name</LI>
										<LI>On
											<UL><LI>Whether or not this <?= $settings->client; ?> has requalification enabled</LI></UL>
										</LI>
										<LI>Frequency
											<UL><LI>How much time passes between anniversaries (a month is exactly 30 days)</LI></UL>
										</LI>
										<LI>From when
											<UL><LI>Requalify on the anniversary of the <?= $settings->profile; ?>'s 'Hired Date' or the 'Anniversary' of a date of your choosing</LI></UL>
										</LI>
										<LI>Products
											<UL><LI>Which products have to be filled out on requalification</LI></UL>
										</LI>
										<LI><?= $settings->profile; ?>s
											<UL>
												<LI>How many <?= $settings->profile; ?>s have requalification enabled / How many <?= $settings->profile; ?>s this <?= $settings->client; ?> has</LI>
												<LI>Clicking this column will show the list of all <?= $settings->profile; ?> with requalification enabled for that <?= $settings->client; ?></LI>
											</UL>
										</LI>
										<LI>Click "<?= $strings["forms_savechanges"]; ?>" to apply any changes</LI>
									</UL>
								</LI>
								<LI><?= $settings->profile; ?>s for [<?= $settings->client; ?>] with requalification enabled
									<UL>
										<LI>Rather than make you edit each <?= $settings->profile; ?>'s settings requalification individually, they have been placed here</LI>
										<LI>ID</LI>
										<LI>Name
											<UL><LI>Clicking this view the <?= $settings->profile; ?></LI></UL>
										</LI>
										<LI><?= $settings->profile; ?> type
											<UL>
												<LI>Lets you change the <?= $settings->profile; ?> type</LI>
												<LI>Types with a ? cannot have orders placed for them.  Types with a ? can.</LI>
											</UL>
										</LI>
										<LI>Expiry Date >= [Today's Date]
											<UL>
												<LI>Lets you change the driver's license expiry date</LI>
												<LI>? = expired (bad), ? = not expired (good)</LI>
											</UL>
										</LI>
										<LI>IH
											<UL><LI>Let's you change the <?= $settings->profile; ?>'s "Is Hired" setting</LI></UL>
										</LI>
										<LI><?= $strings["forms_hireddate"]; ?>, Auto-Change
											<UL>
												<LI>Let's you change the date when the <?= $settings->profile; ?> was hired</LI>
												<LI>The "-X Month(s)" buttons automatically set the "Hired Date" for you, relative to today's date</LI>
											</UL>
										</LI>
										<LI>Click "Save" to apply your changes to that profile</LI>
									</UL>
								</LI>
								<LI><?= $settings->profile; ?> Crons (Requalification)
									<UL>
										<LI>Shows a list of upcoming CRON events</LI>
										<LI>ID</LI>
										<LI>Scheduled Date
											<UL><LI>The date the CRON event will run</LI></UL>
										</LI>
										<LI><?= $settings->client; ?>
											<UL><LI>Clicking this will view the <?= $settings->client; ?></LI></UL>
										</LI>
										<LI>Requalified <?= $settings->profile; ?>
											<UL><LI>Clicking this will view the <?= $settings->profile; ?></LI></UL>
										</LI>
										<LI><?= $strings["forms_hireddate"]; ?>
											<UL><LI>The <?= $settings->profile; ?>'s "<?= $strings["forms_hireddate"]; ?>"</LI></UL>
										</LI>
										<LI>Status
											<UL><LI>The status of the CRON event, and what products it will send (if applicable)</LI></UL>
										</LI>
										<LI>Manual
											<UL>
												<LI>"Yes" means it has been sent already</LI>
												<LI>"Send Now" lets you send the products now</LI>
											</UL>
										</LI>
									</UL>
								</LI>
								<LI>The next 24 months
									<UL><LI>A 2 year calendar showing all CRON events. Hover over the grey squares to see the events for that day</LI></UL>
								</LI>
							</UL>
						</LI>
						<LI><?= $settings->profile; ?> Importer
							<UL>
								<LI>Lets you upload a CSV file of <?= $settings->profile; ?>s to import</LI>
								<LI>Click "Choose File" to select a CSV file, click "Submit" to upload it</LI>
								<LI>The first line of the CSV file must specify the order of "Database Field"s, using the list on that page</LI>
							</UL>
						</LI>
						<LI>Email Editor
							<UL>
								<LI class="red">Warning: All changes to this section must be emailed to the head translator, or they will be deleted on the next update</LI>
								<LI>Rather than have a bunch of emails scattered throughout the source code, they've all been put into the database for convenience</LI>
								<LI>This section lets you edit their Subject and Message, using variable substitution</LI>
								<LI>The code to send emails injects only the variables, and this system substitutes them into the text</LI>
								<LI>Variable names stay the same in every language, they do not get translated</LI>
								<LI>The list of "Global variables" are present in every event
									<UL>
										<LI>%event%
											<UL><LI>%event% will be replaced with the name of the email event being sent (For debugging)</LI></UL>
										</LI>
										<LI>%variables%
											<UL><LI>%variables% will be replaced with a list of all variables being injected/substituted (For debugging)</LI></UL>
										</LI>
										<LI>%webroot%
											<UL><LI>%webroot% will be replaced with <?= $this->request->webroot; ?></LI></UL>
										</LI>
										<LI>%created%
											<UL><LI>%created% will be replaced with the current date/time</LI></UL>
										</LI>
										<LI>%login%
											<UL><LI>%login% will be replaced with <?= LOGIN; ?></LI></UL>
										</LI>
										<LI>%site%
											<UL><LI>%site% will be replaced with <?= $settings->mee; ?></LI></UL>
										</LI>
									</UL>
								</LI>
								<LI>After an email has been sent by the system (not using "Send to yourself"), the list of variables this event has will be saved to "Local Variables"</LI>
								<LI>The left side shows a list of email events, and [New Event] to make a new one</LI>
								<LI>Click "Save" to apply any changes, "Delete" to delete the event, or "Send to yourself" to send an event to yourself (without substituting any variables)</LI>
							</UL>
						</LI>
						<LI>Translation
							<UL>
								<LI class="red">Warning: All changes to this section must be emailed to the head translator, or they will be deleted on the next update</LI>
								<LI>The left side lets you make a "New Language"</LI>
								<LI>Select which language from a list that will be searched for on the right side</LI>
								<LI>Delete the selected language (English and French cannot be deleted)</LI>
								<LI>Or edit a string. If the string Name is unused, it will be created. Click "Save String" to apply the change</LI>
								<LI class="red">Do not edit any string without an <STRONG>_</STRONG> underscore in them as they are used by the system</LI>
								<LI>The right side will let you search for a string based on the string name, or the contents of the string in the language you have selected</LI>
								<LI>Clicking a string on the right side, will let you edit it on the left side</LI>
								<LI>Only a certain number of results are visible at any time, use the page buttons on the bottom right to navigate through them</LI>
							</UL>
						</LI>
						<LI>Product Types
							<UL>
								<LI>Acronym
									<UL><LI>This acts as the ID key for the product, and is used in URLs to link to it/find it</LI></UL>
								</LI>
								<LI>Panel Color
									<UL><LI>What color will show when selecting products</LI></UL>
								</LI>
								<LI>Button Color
									<UL><LI>What color the buttons will show as</LI></UL>
								</LI>
								<LI>Checked
									<UL><LI>If enabled, all products will be selected and the user cannot pick any packages</LI></UL>
								</LI>
								<LI>Visible
									<UL><LI>If disabled, it will not show in the sidebar or settings</LI></UL>
								</LI>
								<LI>Bypass
									<UL><LI>If enabled, the top block will use Driver ID 0 and skip the driver/client selection screen</LI></UL>
								</LI>
								<LI>Sidebar Alias
									<UL><LI>Needs to point to a column in the sidebar table, this column stores the permission</LI></UL>
								</LI>
								<LI>Blocks Alias
									<UL><LI>Needs to point to a column in the blocks table, this column stores the permission</LI></UL>
								</LI>
								<LI>Make Column
									<UL><LI>Makes a new column in the specified table to store a new permission in. <SPAN ONCLICK="expand('clear-data/clear-cache');">Clear the cache</SPAN> after doing this</LI></UL>
								</LI>
								<LI><?= $languages; ?> Name and Description</LI>
								<LI>Top Block Color
									<UL><LI>What color will the Top blocks show as</LI></UL>
								</LI>
								<LI>Price</LI>
								<LI>Icon
									<UL><LI>What icon will show</LI></UL>
								</LI>
								<LI>Show only these packages
									<UL><LI>If this is not blank, only the packages you select from the list will be shown</LI></UL>
								</LI>
								<LI>Product/<?= $settings->document; ?> IDs
									<UL><LI>If Bypass is enabled: Which products will show when a topblock is clicked. Otherwise it's which forms will show when placing an order</LI></UL>
								</LI>
								<LI>Show for <?= $settings->profile; ?> types
									<UL><LI>Will only show for these profile types when viewing a profile</LI></UL>
								</LI>
							</UL>
						</LI>
					</UL>
				</LI>
				<LI>Email Log
					<UL>
						<LI>Views the debug log file</LI>
						<LI>If the email system is disabled, all outgoing emails will be saved here</LI>
						<LI>Other systems use it to show information in case there's no way to show it to the user</LI>
						<LI>If it's not empty, it also allows you to delete it</LI>
					</UL>
				</LI>
			</ul>
	  	<?php } ?>
	</li>

	<li id="thesidebar">The sidebar
		<ul>
			<LI><?= $settings->document; ?> Search...
				<UL>
					<LI>A shortcut to <SPAN ONCLICK="expand('documents/listdocuments');"><?= $strings["index_listdocuments"]; ?></SPAN> searching for the text you specify</LI>
				</UL>
			</LI>
			<LI><?= $strings["dashboard_dashboard"]; ?>
				<UL>
					<LI>This is the main/home page that will show shortcuts (top blocks) to various sections and documents as well as a list of clients</LI>
					<LI>If you do not have the appropriate permissions, you may not see this screen and instead will be redirected to a section you do have permissions for</LI>
				</UL>
			</LI>
			<LI ID="clients">
				<?= $settings->client; ?>
				<UL>
					<LI ID="list-clients">
						<?= $strings["index_listclients"]; ?>
						<UL>
							<LI><?= $strings["index_createclients"]; ?>
								<UL>
									<LI ONCLICK="expand('client-actions/edit-client');">Opens a blank <?= $settings->client; ?> information page for you to create a new <?= $settings->client; ?></LI>
								</UL>
							</LI>
							<LI><?= $strings["clients_search"]; ?></LI>
							<LI>ID</LI>
							<LI><?= $strings["clients_logo"]; ?></LI>
							<LI><?= $settings->client; ?>
								<UL><LI>Clicking this will view the information for this <?= $settings->client; ?></LI></UL>
							</LI>
							<LI ID="client-actions"><?= $strings["dashboard_actions"];?>
								<UL>
									<LI id="edit-client"><?= $strings["dashboard_view"]; ?>/<?= $strings["dashboard_edit"]; ?>
										<UL>
											<LI>Here is where you can view, create or edit a <?= $settings->client; ?></LI>
											<LI><?= $strings["clients_addeditimage"]; ?></LI>
											<LI><?= $strings["index_listprofile"]; ?>
												<UL><LI>Links to the <SPAN ONCLICK="expand('thesidebar/profiles/list-profiles');"><?= $strings["index_listprofile"]; ?></SPAN> section searching for <?= $settings->profile; ?>s assigned to this <?= $settings->client; ?></LI></UL>
											</LI>
											<LI><?= $strings["dashboard_edit"]; ?>/<?= $strings["dashboard_view"]; ?>
												<UL><LI>Switch between edit and view mode</LI></UL>
											</LI>
											<LI><?= $strings["dashboard_delete"]; ?>
												<UL><LI>Delete this <?= $settings->client; ?></LI></UL>
											</LI>
											<LI>Info
												<UL><LI>Lets you edit the data for this <?= $settings->client; ?></LI></UL>
											</LI>
											<LI>Products
												<UL>
													<LI>Lets you enable products globally (for everyone) and locally (for this <?= $settings->client; ?>)</LI>
													<LI>A product needs to be enabled both globally and locally for it to show up for a <?= $settings->client; ?></LI>
													<LI>Changes are saved as you make them</LI>
												</UL>
											</LI>
											<LI><?= $settings->document; ?>
												<UL>
													<LI>Document Yes/No</LI>
													<LI>Orders</LI>
													<LI>Application Process
														<UL><LI>Sets whether or not this document shows in the clientapplication process</LI></UL>
													</LI>
													<LI>Display Order
														<UL><LI>The display order can be changed only by clicking and dragging the row to a new position</LI></UL>
													</LI>
													<LI>Click "<?= $strings["forms_savechanges"]; ?>" to apply the changes</LI>
												</UL>
											</LI>
											<LI>Assign to <?= $settings->profile; ?>
												<UL>
													<LI>Lets you search for <?= $settings->profile; ?>s to assign to this <?= $settings->client; ?></LI>
													<LI>Type in the text box to search for <?= $settings->profile; ?>s with that text in them</LI>
													<LI>Clicking a profile assigns it to the current <?= $settings->client; ?></LI>
													<LI>Changes are saved as you make them</LI>
												</UL>
											</LI>
										</UL>
									</LI>
									<LI><?= $strings["dashboard_delete"]; ?></LI>
								</UL>
							</LI>
						</UL>
					</LI>
					<LI id="create-client"><?= $strings["index_createclients"]; ?>
						<UL>
							<LI ONCLICK="expand('list-clients/client-actions/edit-client');">Opens a blank <?= $settings->client; ?> information page for you to create a new <?= $settings->client; ?></LI>
						</UL>
					</LI>
				</UL>
			</LI>
			<LI id="profiles">
				<?= $settings->profile; ?>s
				<UL>
					<LI id="list-profiles"><?= $strings["index_listprofile"]; ?>
						<UL>
							<LI ONCLICK="expand('profile');" CLASS="blue"><?= $strings["index_createprofile"]; ?></LI>
							<LI>Search
								<UL>
									<LI><?= $strings["profiles_profiletype"]; ?>
										<UL><LI>Search for <?= $settings->profile; ?>s only matching this type</LI></UL>
									</LI>
									<LI><?= $settings->client; ?>
										<UL><LI>Search for <?= $settings->profile; ?>s assigned to this <?= $settings->client; ?></LI></UL>
									</LI>
									<LI><?= $strings["profiles_searchfor"]; ?>
										<UL><LI>Search for <?= $settings->profile; ?>s with this text in their name</LI></UL>
									</LI>
									<LI><?= $strings["dashboard_search"]; ?>
										<UL><LI>Run the search using the above parameters</LI></UL>
									</LI>
								</UL>
							</LI>
							<LI>ID</LI>
							<LI><?= $strings["profiles_image"]; ?></LI>
							<LI><?= $strings["profiles_name"]; ?></LI>
							<LI><?= $strings["profiles_profiletype"]; ?></LI>
							<LI><?= $strings["profiles_assignedto"]; ?>
								<UL><LI>A list of <?= $settings->client; ?>s this <?= $settings->profile; ?> is assigned to</LI></UL>
							</LI>
							<LI><?= $strings["dashboard_actions"]; ?>
								<UL>
									<LI><?= $strings["dashboard_view"]; ?>/<?= $strings["dashboard_edit"]; ?>
										<UL><LI>Open this <?= $settings->profile; ?> for viewing/editing</LI></UL>
									</LI>
									<LI><?= $strings["dashboard_delete"]; ?>
										<UL><LI>Delete this <?= $settings->profile; ?></LI></UL>
									</LI>
									<?php if($IsSuper) { ?>
										<LI>Possess
											<UL><LI>This feature is useful for when a user reports an issue, and you can temporarily log in as them to check if it's a result of their permissions</LI></UL>
										</LI>
									<?php } ?>
								</UL>
							</LI>
						</UL>
					</LI>
					<LI id="profile"><?= $strings["index_createprofile"]; ?>
						<UL>
							<LI><?= $strings["clients_addeditimage"]; ?>
								<UL><LI>Edit the image that shows up in the header for this <?= $settings->profile; ?>, as well as the <?= $strings["index_listprofile"]; ?> page</LI></UL>
							</LI>
							<LI><?= $strings["clients_enablerequalify"]; ?>
								<UL><LI>Sets whether or not this user will recieve forms on a regular basis automatically by the system, if it's enabled for their <?= $settings->client; ?></LI></UL>
							</LI>
							<LI><?= $strings["profiles_washired"]; ?>
								<UL><LI>Sets whether or not this <?= $settings->profile; ?> has been hired</LI></UL>
							</LI>
							<LI><?= $OrderTypes; ?></LI>
							<LI><?= $strings["dashboard_view"]; ?>/<?= $strings["dashboard_edit"]; ?>
								<UL><LI>Switch between <?= $strings["dashboard_view"]; ?> and <?= $strings["dashboard_edit"]; ?> mode</LI></UL>
							</LI>
							<LI><?= $strings["dashboard_delete"]; ?>
								<UL><LI>Delete this <?= $settings->profile; ?></LI></UL>
							</LI>
							<?php if($IsSuper){ ?>
								<LI>Possess
									<UL><LI>Click this to log in as this user, for the purpose of testing</LI></UL>
								</LI>
							<?php } ?>
							<LI><?= $strings["orders_scorecard"]; ?></LI>
							<LI id="profile-info"><?= $settings->profile; ?>
								<UL>
									<LI>Lets you edit the data for this profile</LI>
									<LI>Assign to <?= $settings->client; ?>
										<UL><LI>A non-super <?= $settings->profile; ?> can only be assigned to a single <?= $settings->client; ?></LI></UL>
									</LI>
								</UL>
							</LI>
							<LI id="permissions">Permissions
								<UL>
									<LI>(Sidebar) Configuration
										<UL>
											<LI>Select All
												<UL><LI>Checks all checkboxes automatically</LI></UL>
											</LI>
											<LI>Change all existing <?= $settings->profile; ?>s of this type
												<UL><LI>Once you click save, all <?= $settings->profile; ?>s of the same <?= $settings->profile; ?> type will have their permissions over-written with this <?= $settings->profile; ?>'s permissions</LI></UL>
											</LI>
											<LI>Change all future <?= $settings->profile; ?>s of this type
												<UL><LI>Once you click save, all <?= $settings->profile; ?>s of the same <?= $settings->profile; ?> type you make afterwards will start with the same permissions as this <?= $settings->profile; ?>'s</LI></UL>
											</LI>
											<LI>Enable <?= $settings->profile; ?>
												<UL>
													<LI>Yes/No
														<UL><LI>Sets whether or not the rest of the settings for this category will show</LI></UL>
													</LI>
													<LI>List
														<UL><LI>Required to see and use the <?= $strings["index_listprofile"] ?> page</LI></UL>
													</LI>
													<LI>Create
														<UL><LI>Required to make new <?= $settings->profile; ?>s of the types enabled below</LI></UL>
													</LI>
													<LI><?= $strings["dashboard_edit"]; ?>
														<UL><LI>Required to edit <?= $settings->profile; ?>s</LI></UL>
													</LI>
													<LI><?= $strings["dashboard_delete"]; ?>
														<UL><LI>Required to delete <?= $settings->profile; ?>s</LI></UL>
													</LI>
													<LI>Receive Email (on create <?= $settings->profile; ?>)
														<UL><LI>If enabled, this <?= $settings->profile; ?> will recieve an email when ever a new <?= $settings->profile; ?> is created</LI></UL>
													</LI>
													<LI><?= $ProfileTypes; ?>
														<UL><LI>This <?= $settings->profile; ?> will only be able to see and create <?= $settings->profile; ?>s only of these types</LI></UL>
													</LI>
												</UL>
											</LI>
											<LI>Enable <?= $settings->client; ?>
												<UL>
													<LI>Yes/No
														<UL><LI>Sets whether or not the rest of the settings for this category will show</LI></UL>
													</LI>
													<LI>List
														<UL><LI>Required to see and use the <SPAN ONCLICK="expand('list-clients');"><?= $strings["index_listclients"]; ?></SPAN> page</LI></UL>
													</LI>
													<LI>Create
														<UL><LI>Required to make new <?= $settings->client; ?>s of the types enabled below</LI></UL>
													</LI>
													<LI><?= $strings["dashboard_edit"]; ?>
														<UL><LI>Required to edit <?= $settings->client; ?>s</LI></UL>
													</LI>
													<LI><?= $strings["dashboard_delete"]; ?>
														<UL><LI>Required to delete <?= $settings->client; ?>s</LI></UL>
													</LI>
													<LI><?= $ClientTypes; ?>
														<UL><LI>This <?= $settings->profile; ?> will only be able to see and create <?= $settings->client; ?>s only of these types</LI></UL>
													</LI>
												</UL>
											</LI>
											<LI>Enable <?= $strings["index_orders"]; ?>
												<UL>
													<LI>Yes/No
														<UL><LI>Sets whether or not the rest of the settings for this category will show</LI></UL>
													</LI>
													<LI>List
														<UL><LI>Required to see and use the <?= $strings["index_listorders"]; ?> page</LI></UL>
													</LI>
													<LI>Create
														<UL><LI>Required to make new orders of the types enabled below</LI></UL>
													</LI>
													<LI><?= $strings["dashboard_edit"]; ?>
														<UL><LI>Required to edit orders</LI></UL>
													</LI>
													<LI><?= $strings["dashboard_delete"]; ?>
														<UL><LI>Required to delete orders</LI></UL>
													</LI>
													<LI>Receive Email (on create <?= $strings["index_orders"]; ?>)
														<UL><LI>If enabled, this <?= $settings->profile; ?> will recieve an email when ever a new order is created within Veritas</LI></UL>
													</LI>
													<LI>Receive Email (on client application completion)
														<UL><LI>If enabled, this <?= $settings->profile; ?> will recieve an email when ever a new order is created within the ClientApplication system (a version of Veritas that does not require logging in)</LI></UL>
													</LI>
													<LI><?= $OrderTypes; ?>
														<UL><LI>This <?= $settings->profile; ?> will only be able to see and create orders only of these types</LI></UL>
													</LI>
												</UL>
											</LI>
											<LI>Enable <?= $settings->document; ?>
												<UL>
													<LI>Yes/No
														<UL><LI>Sets whether or not the rest of the settings for this category will show</LI></UL>
													</LI>
													<LI>List
														<UL><LI>Required to see and use the <SPAN ONCLICK="expand('documents/listdocuments');"><?= $strings["index_listdocuments"]; ?></SPAN> page</LI></UL>
													</LI>
													<LI>Create
														<UL><LI>Required to create <?= $settings->document; ?>s</LI></UL>
													</LI>
													<LI><?= $strings["dashboard_edit"]; ?>
														<UL><LI>Required to edit <?= $settings->document; ?>s</LI></UL>
													</LI>
													<LI><?= $strings["dashboard_delete"]; ?>
														<UL><LI>Required to delete <?= $settings->document; ?>s</LI></UL>
													</LI>
													<LI>Receive Email (on create <?= $settings->document; ?>)
														<UL><LI>If enabled, this <?= $settings->profile; ?> will recieve an email whenever a <?= $settings->document; ?> is created</LI></UL>
													</LI>
													<LI><?= $DocumentTypes; ?>
														<UL>
															<LI>None
																<UL><LI>This <?= $settings->profile; ?> will not be able to do anything with this <?= $settings->document; ?> type</LI></UL>
															</LI>
															<LI><?= $strings["dashboard_view"]; ?> Only
																<UL><LI>This <?= $settings->profile; ?> will only be able to see this <?= $settings->document; ?> type</LI></UL>
															</LI>
															<LI>Create Only
																<UL><LI>This <?= $settings->profile; ?> will only be able to create this <?= $settings->document; ?> type</LI></UL>
															</LI>
															<LI>Both
																<UL><LI>This <?= $settings->profile; ?> will be able to see and create this <?= $settings->document; ?> type</LI></UL>
															</LI>
														</UL>
													</LI>
												</UL>
											</LI>
											<LI>Enable <?= $strings["index_tasks"]; ?>
												<UL><LI>Required to see and use the <?= $strings["index_tasks"]; ?> page</LI></UL>
											</LI>
											<LI>Enable <?= $strings["index_addtasks"]; ?>
												<UL><LI>Required to see and use the <?= $strings["index_addtasks"]; ?> page</LI></UL>
											</LI>
											<LI>Enable <?= $strings["index_analytics"]; ?>
												<UL><LI>Required to see and use the <?= $strings["index_analytics"]; ?> page</LI></UL>
											</LI>
											<LI>Enable <?= $strings["index_training"]; ?>
												<UL><LI>Required to see and use the <?= $strings["index_training"]; ?> page</LI></UL>
											</LI>
											<LI>Enable Show Logo</LI>
										</UL>
									</LI>
									<LI>Top blocks
										<UL>
											<LI>These add top blocks to the <?= $settings->profile; ?>'s dashboard which act as shortcuts to another page</LI>
											<LI>Add a <?= $settings->profile; ?>
												<UL><LI>Shortcut to the '<?= $strings["index_createprofile"]; ?>' page</LI></UL>
											</LI>
											<LI ONCLICK="expand('profiles/list-profiles');" CLASS="blue"><?= $strings["index_listprofile"]; ?></LI>
											<LI ONCLICK="expand('clients/client-actions/edit-client/create-client');" CLASS="blue">Add a <?= $settings->client; ?></LI>
											<LI ONCLICK="expand('clients/list-clients');" CLASS="blue"><?= $strings["index_listclients"]; ?></LI>
											<LI ONCLICK="expand('documents/create-document');" CLASS="blue">Submit <?= $settings->document; ?></LI>
											<LI ONCLICK="expand('documents/listdocuments');" CLASS="blue"><?= $strings["index_listdocuments"]; ?></LI>
											<LI ONCLICK="expand('training');" CLASS="blue"><?= $strings["index_training"]; ?></LI>
											<LI ONCLICK="expand('orders/create-order');" CLASS="blue"><?= $OrderTypes; ?></LI>
											<LI ONCLICK="expand('orders/list-orders');" CLASS="blue"><?= $strings["index_listorders"]; ?></LI>
											<LI ONCLICK="expand('tasks');" CLASS="blue"><?= $strings["index_tasks"]; ?></LI>
											<LI ONCLICK="expand('tasks/add-task');" CLASS="blue"><?= $strings["index_addtasks"]; ?></LI>
											<LI><?= $settings->document; ?>s Drafts
												<UL><LI>Opens <SPAN ONCLICK="expand('documents/list-document');"><?= $strings["index_listdocuments"]; ?></SPAN> searching for drafts</LI></UL>
											</LI>
											<LI><?= $strings["index_orders"]; ?> Drafts
												<UL><LI>Opens <SPAN ONCLICK="expand('orders/list-orders');"><?= $strings["index_listorders"]; ?></SPAN> searching for drafts</LI></UL>
											</LI>
											<LI ONCLICK="expand('analytics');" CLASS="blue"><?= $strings["index_analytics"]; ?></LI>
											<LI>Bulk Order</LI>
										</UL>
									</LI>
									<LI><?= $strings["forms_savechanges"]; ?>
										<UL><LI>Click this to save your changes</LI></UL>
									</LI>
								</UL>
							</LI>
							<LI>Notes
								<UL><LI>Lets you add/edit/delete notes for a <?= $settings->profile; ?></LI></UL>
							</LI>
							<LI>Message
								<UL><LI>Lets you send an email to this <?= $settings->profile; ?></LI></UL>
							</LI>
						</UL>
					</LI>
				</UL>
			</LI>
			<LI id="training">
				<?= $strings["index_training"]; ?>
				<UL>
					<LI><?= $strings["index_courses"]; ?>
						<UL>
							<?php if($IsSuper){ ?>
								<LI>View
									<UL><LI>Lets you see how regular <?= $settings->profile; ?> see this page once they've selected a course</LI></UL>
								</LI>
								<LI>Preview
									<UL><LI>Lets you see the questions/answers how regular <?= $settings->profile; ?> would see them</LI></UL>
								</LI>
								<LI id="training-enroll">Enroll
									<UL>
										<LI>A mini version of <SPAN ONCLICK="expand('profiles/list-profiles');"><?= $strings["index_listprofile"]; ?></SPAN> to search for, and enroll <?= $settings->profile; ?> in a course</LI>
										<LI>Once you enroll a <?= $settings->profile; ?>, their <?= $strings["index_training"]; ?> permission will be enabled, and they will recieve an email telling them where to take the course </LI>
									</UL>
								</LI>
								<LI id="training-results">Results</LI>
								<LI>Edit
									<UL>
										<LI>Delete</LI>
										<LI>Export
											<UL><LI>Exports the course and it's questions/answers as SQL for copying to another server/database</LI></UL>
										</LI>
										<LI>Quiz Name</LI>
										<LI>Image
											<UL><LI>Lets you pick an image from <?= getcwd() . "/img"; ?> to go next to the course</LI></UL>
										</LI>
										<LI>Attachments
											<UL>
												<LI>A comma separated value (CSV) list of attachments</LI>
												<LI>ie: attachment1.pdf,attachment2.pdf</LI>
												<LI>Attachments must be stored in: <?= getcwd() . "/assets/global/" ?></LI>
												<LI>The spelling and case of the attachments must match exactly</LI>
												<LI>The system supports MP4, PDF and DOCX files</LI>
												<LI>To link to an MP4 file, make one of the CSV list items:</LI>
												<LI>training/video?title=<SPAN class="red">Title of the video</SPAN>&url=<SPAN class="red">Full URL to the video</SPAN></LI>
											</UL>
										</LI>
										<LI>Description</LI>
										<LI>Pass
											<UL><LI>What percentage is required to pass the course</LI></UL>
										</LI>
										<LI>Certificate
											<UL><LI>Whether or not passing <?= $settings->profile; ?>s get a certificate</LI></UL>
										</LI>
										<LI>Click "<?= $strings["forms_savechanges"]; ?>" to apply your changes</LI>
										<LI ONCLICK="expand('training-results');" CLASS="blue">Results</LI>
										<LI ONCLICK="expand('training-enroll');" CLASS="blue">Enroll</LI>
										<LI>Preview
											<UL><LI>Lets you see the questions/answers how regular <?= $settings->profile; ?> would see them</LI></UL>
										</LI>
										<LI>Preview with answers
											<UL><LI>Lets you see the questions/answers with the correct answer selected/LI></UL>
										</LI>
										<LI>Question list
											<UL>
												<LI>Edit
													<UL>
														<LI>Question</LI>
														<LI>Image
															<UL><LI>Lets you pick an image from <?= getcwd() . "/img/training"; ?> to go next to the question</LI></UL>
														</LI>
														<LI>Answers a-f</LI>
														<LI>True/False
															<UL><LI>A shortcut to set answer a to "True", and b to "False"</LI></UL>
														</LI>
														<LI>Click "<?= $strings["forms_savechanges"]; ?>" to apply your changes</LI>
													</UL>
												</LI>
												<LI>Delete</LI>
											</UL>
										</LI>
									</UL>
								</LI>
								<LI>Delete</LI>
							<?php } else { ?>
								<LI>A list of courses you are enrolled in</LI>
								<LI>Clicking one will ask you to view each attachment in sequential order, then click Quiz to take the course</LI>
								<LI>Once you are done selecting your responses, click the button at the bottom to save your choices</LI>
								<LI>It will ask you one last time if you're sure that you are done, just to be safe</LI>
							<?php } ?>
						</UL>
					</LI>
					<?php if($IsSuper){ ?>
						<LI><?= $strings["index_quizresults"];?>
							<UL>
								<LI>Shows a list of courses to choose from</LI>
								<LI>Once a course is selected, it then shows each <?= $settings->profile; ?> enrolls in that course, a link to their <?= $settings->profile; ?>, their score, and the option to Unenroll them</LI>
							</UL>
						</LI>
					<?php } ?>
				</UL>
			</LI>
			<LI id="documents">
				<?= $settings->document; ?>
				<UL>
					<LI id="listdocuments"><?= $strings["index_listdocuments"]; ?>
						<UL>
							<LI><?= $strings["index_createdocument"]; ?>
							<LI>Search
								<UL>
									<LI><?= $strings["index_listdocuments"]; ?>
										<UL><LI>Select whether you want to show completed documents, or just drafts</LI></UL>
									</LI>
									<LI><?= $settings->document . '/' . $strings["documents_submittedby"] . '/' . $strings["documents_submittedfor"] . '/' . $settings->client; ?>
										<UL><LI>Search by these columns</LI></UL>
									</LI>
									<LI><?= $strings["dashboard_search"]; ?>
										<UL><LI>This will run the search using the previous parameters</LI></UL>
									</LI>
								</UL>
							</LI>
							<LI>ID</LI>
							<LI><?= $settings->document; ?>
								<UL><LI>What type of <?= $settings->document; ?> this is</LI></UL>
							</LI>
							<LI><?= $strings["documents_orderid"]; ?>
								<UL><LI>If this <?= $settings->document; ?> is part of an order, clicking this will open the order</LI></UL>
							</LI>
							<LI><?= $strings["documents_submittedby"]; ?>
								<UL><LI>Who made/filled out the <?= $settings->document; ?></LI></UL>
							</LI>
							<LI><?= $strings["documents_submittedfor"]; ?>
								<UL><LI>Who the <?= $settings->document; ?> was filled out for</LI></UL>
							</LI>
							<LI><?= $strings["documents_created"]; ?>
								<UL>
									<LI>The date/time the document was created at</LI>
									<LI>The color indicates how old the order is:</LI>
									<LI>Less than 1 day</LI>
									<LI class="green">1-2 days</LI>
									<LI class="yellow">2-7 days</LI>
									<LI class="red">Older than 1 week</LI>
								</UL>
							</LI>
							<LI><?= $settings->client; ?>
								<UL><LI>What <?= $settings->client; ?> the <?= $settings->document; ?> was filled out for</LI></UL>
							</LI>
							<LI><?= $strings["dashboard_actions"]; ?>
								<UL>
									<LI><?= $strings["dashboard_view"] . '/' . $strings["dashboard_edit"]; ?>
										<UL><LI>Open the <?= $settings->document; ?> for viewing/editing</LI></UL>
									</LI>
									<LI><?= $strings["dashboard_delete"]; ?>
										<UL><LI>Delete the <?= $settings->document; ?></LI></UL>
									</LI>
								</UL>
							</LI>
							<LI><?= $strings["documents_status"]; ?>
								<UL>
									<LI>Shows if the <?= $settings->document; ?> is completed (<?= $strings["documents_saved"]; ?>) or not (<?= $strings["documents_draft"]; ?>)</LI>
								</UL>
							</LI>
						</UL>
					</LI>
					<LI id="create-document"><?= $strings["index_createdocument"]; ?>
						<UL>
							<LI><?= $strings["infoorder_selectclient"]; ?>
								<UL><LI>Select which <?= $settings->client; ?> the <?= $settings->document; ?> will be submitted for</LI></UL>
							</LI>
							<LI><?= $strings["documents_selectdocument"]; ?>
								<UL><LI>Select which division of the <?= $settings->client; ?> (if applicable) the <?= $settings->document; ?> will be submitted for</LI></UL>
							</LI>
							<LI><?= $strings["forms_selectdriver"]; ?>
								<UL><LI>Select which <?= $settings->profile; ?>  of the <?= $settings->client; ?> the <?= $settings->document; ?> will be submitted for</LI></UL>
							</LI>
							<LI><?= $strings["forms_save"]; ?>
								<UL><LI>Save the <?= $settings->document; ?> and process it as complete</LI></UL>
							</LI>
							<LI><?= $strings["forms_savedraft"]; ?>
								<UL><LI>Save the <?= $settings->document; ?> as incomplete, to let you finish it later</LI></UL>
							</LI>
						</UL>
					</LI>
				</UL>
			</LI>
			<LI id="orders">
				<?= $strings["index_orders"]; ?>
				<UL>
					<LI id="list-orders"><?= $strings["index_listorders"]; ?>
						<UL>
							<LI>Search
								<UL>
									<LI><?= $strings["documents_select"]; ?>
										<UL><LI>Search by the status column</LI></UL>
									</LI>
									<LI><?= $strings["documents_submittedby"] . '/' . $strings["documents_submittedfor"]; ?>
										<UL><LI>Search by these columns</LI></UL>
									</LI>
									<LI><?= $strings["orders_search"]; ?>
										<UL><LI>Search for orders with this text in the title or type</LI></UL>
									</LI>
								</UL>
							</LI>
							<LI>ID</LI>
							<LI><?= $strings["orders_ordertype"]; ?></LI>
							<LI><?= $strings["documents_submittedby"]; ?>
								<UL><LI>The <?= $settings->profile; ?> that submitted the order</LI></UL>
							</LI>
							<LI><?= $strings["documents_submittedfor"]; ?>
								<UL><LI>The <?= $settings->profile; ?> the order was submitted for</LI></UL>
							</LI>
							<LI><?= $settings->client; ?>
								<UL><LI>The <?= $settings->client; ?> the order was submitted for</LI></UL>
							</LI>
							<LI><?= $strings["orders_division"]; ?></LI>
							<LI><?= $strings["documents_created"]; ?>
								<UL>
									<LI>The date/time the order was placed at</LI>
									<LI>The color indicates how old the order is:</LI>
									<LI>Less than 1 day</LI>
									<LI class="green">1-2 days</LI>
									<LI class="yellow">2-7 days</LI>
									<LI class="red">Older than 1 week</LI>
								</UL>
							</LI>
							<LI><?= $strings["dashboard_actions"]; ?></LI>
							<LI><?= $strings["documents_status"]; ?></LI>
						</UL>
					</LI>
					<LI id="create-order"><?= $OrderTypes; ?>
						<UL>
							<LI>Lets you place an order of this type</LI>
							<LI><?= $settings->client; ?></LI>
							<LI><?= $strings["orders_division"]; ?></LI>
							<LI><?= $strings["infoorder_driver"]; ?>(s)
								<UL><LI>Select which <?= $settings->profile; ?>(s) that are assigned to this <?= $settings->client; ?> that the order will be placed for</LI></UL>
							</LI>
							<LI>A list of packages</LI>
							<LI><?= $strings["infoorder_continue"]; ?>
								<UL>
									<LI><?= $strings["forms_savedraft"]; ?>
										<UL><LI>Save the order as a draft, so you can finish filling it out later</LI></UL>
									</LI>
									<LI><?= $strings["dashboard_previous"] . '/' . $strings["dashboard_next"]; ?>
										<UL><LI>Navigate back/ahead 1 <?= $settings->document; ?> in the order</LI></UL>
									</LI>
									<LI>'<?= $strings["consent_withinborder"]; ?>' on the consent form
										<UL><LI>These boxes require you to give your signature using the mouse (or your finger on a touchscreen). If your mouse/finger leaves the boundaries of the box before you lift your finger off the button/screen, it will not realize you signed it and will have to draw another line without leaving the boundaries. Pressing "Clear" will erase what you've drawn to let you try again</LI></UL>
									</LI>
									<LI><?= $strings["forms_save"]; ?>
										<UL><LI>On the last page, click this to save the order and process it as completed</LI></UL>
									</LI>
								</UL>
							</LI>
						</UL>
					</LI>
				</UL>
			</LI>
			<LI id="analytics">
				<?= $strings["index_analytics"]; ?>
				<UL>
					<LI>Allows you to view statistics on user activity between 2 dates using the datepickers at the top right. Defaults to the last 2 weeks.</LI>
					<LI><?= $settings->client . 's, ' . $settings->profile . 's, ' . $settings->document; ?>'s, Orders created and their type</LI>
					<LI><?= $strings["index_training"]; ?> courses completed</LI>
				</UL>
			</LI>
			<LI id="tasks">
				<?= $strings["index_tasks"]; ?>
				<UL>
					<LI><?= $strings["index_calendar"]; ?>
						<UL>
							<LI ONCLICK="expand('add-task');" CLASS="blue">Add Task</LI>
							<?php if($IsSuper){ ?>
								<LI>Run the CRON
									<UL><LI>Any events with a date/time before now will be triggered, but not marked as such. This is for testing purposes</LI></UL>
								</LI>
								<LI>Send test email
									<UL><LI>Sends a test email to yourself</LI></UL>
								</LI>
							<?php } ?>
							<LI>today
								<UL><LI>Moves the calendar to today</LI></UL>
							</LI>
							<LI>&lt; and &gt; (top right corner)
								<UL><LI>Moves the calendar back/ahead a month</LI></UL>
							</LI>
							<LI>The calendar
								<UL>
									<LI>Clicking a day shows a list of events for that day. Clicking an event lets you <SPAN ONCLICK="expand('add-task');">edit it</SPAN></LI>
									<LI>Hovering your mouse over an event shows a preview on the left side, and gives you the option to delete it</LI>
								</UL>
							</LI>
						</UL>
					</LI>
					<LI id="add-task"><?= $strings["index_addtasks"]; ?>
						<UL>
							<LI><?= $strings["tasks_date"]; ?>
								<UL>
									<LI>Clicking this opens the date/time dropdown, to make it easier/faster to properly enter a date/time</LI>
								</UL>
							</LI>
							<LI><?= $strings["tasks_title"]; ?></LI>
							<LI><?= $strings["tasks_description"]; ?></LI>
							<LI><?= $strings["tasks_2yourself"]; ?>
								<UL><LI>If checked, once this date/time passes an email will be sent to yourself</LI></UL>
							</LI>
							<LI><?= $strings["tasks_2others"]; ?>
								<UL><LI>A comma separated value (CSV) list of email addresses to get emailed when this date/time passes</LI></UL>
							</LI>
							<LI>Click "<?= $strings["forms_savechanges"]; ?>" to apply the changes</LI>
						</UL>
					</LI>
				</UL>
			</LI>
		</ul>
	</li>

	<li ID="thecontent">The content
	  <ul>
		<li>This is where the page you're on will be shown</li>
		<li>The arrow that sometimes appears at the bottom-right corner of this section will scroll you back to the top of the page</li>
		<LI ID="actionbar">Action bar
			<UL>
				<LI>The bar at the top of most pages will show the name of the page you're on</LI>
				<LI>Breadcrumb navigation to go up your history to the dashboard</LI>
				<LI>And on the right side, some buttons may be present like <?= $strings["dashboard_print"] ?> </LI>
			</UL>
		</LI>
		<LI ID="paginationbar">Pagination bar
			<UL>
				<LI>On pages that have too many items to fit on a single page, it will be split up into multiple pages</LI>
				<LI>This bar will let you go to the first/last page, and a few surrounding the page you're on</LI>
			</UL>
		</LI>
	  </ul>
	</li>
  </ul>
</div>
<BUTTON ONCLICK="ExpandAll();">Expand All</BUTTON>
<BUTTON ONCLICK="CollapseAll();">Collapse All</BUTTON>