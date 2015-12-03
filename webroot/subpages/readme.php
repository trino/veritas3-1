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
			}
		} else {
			element = document.getElementById(ID);
			if(!element.hasClass("expanded")) {
				element.click();
			}
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
	$language = "English";//only english is supported
	$strings = CacheTranslations($language, array("clients_%", "profiles_washired", "orders_scorecard", "forms_savechanges"), $settings);
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
<P>Clicking a section of this page will expand it to show more information about it</P>
<P>What's visible will be dependant on the permissions of both you, and the <?= $settings->client ?>(s) you are assigned to</P>
<P>There are 4 parts to the Veritas screen</P>
<div id="listContainer">
  <ul id="expList">
	<li id="theheader">The header: (along the top)
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
					<UL>
						<LI>If you have possessed a <?= $settings->profile ?>, click this to log back in as yourself</LI>
					</UL>
				</LI>
			<?php } ?>
		</ul>
	</li>

	<LI id="thefooter">The footer: (along the bottom)
		<ul>
			<li>Some pages will show a list of checkboxes on the left side to indicate what permissions they use (visible when you hover your mouse over the checkbox) and if you have them enabled</li>
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
								<LI>Clicking an image will select it, click "Save Changes" to apply the change</LI>
								<LI>Click "Add new logo" or "<?= $strings["clients_addeditimage"]; ?>" to upload an image</LI>
								<LI>Changes to the <?= $settings->client; ?> logo will be applied without needing to click "Save Changes" </LI>
							</UL>
						</LI>
						<LI id="pages">Pages
							<UL>
								<LI>You can customize the titles and descriptions for the <?= $titles; ?> pages for each language here</LI>
								<LI>Click "Save Changes" to apply the change</LI>
							</UL>
						</LI>
						<LI>Display
							<UL>
								<LI>Edit how the words: <?= $settings->client . ', ' . $settings->profile  . ', ' . $settings->document; ?> for each language appear throughout the page as well as the site name (<?= $settings->mee; ?>)</LI>
								<LI>Click "Save Changes" to apply the change</LI>
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
											<UL>
												<LI>Sets whether or not orders can be placed for this <?= $settings->profile; ?> type</LI>
											</UL>
										</LI>
									</UL>
								</LI>
								<LI><?= $settings->client; ?> types</LI>
								<LI><?= $settings->document; ?>s
									<UL>
										<LI>Color</LI>
										<LI>Icon</LI>
										<LI>Product</LI>
										<LI>Delete</LI>
									</UL>
								</LI>
								<LI>Click "Edit" to let you rename them, then "Save" to apply the changes</LI>
							</UL>
						<LI>Clear Data
							<UL>
								<LI>Clear Data</LI>
								<LI>Scramble Data</LI>
								<LI>Clear Cache</LI>
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
											<UL>
												<LI>Whether or not this <?= $settings->client; ?> has requalification enabled</LI>
											</UL>
										</LI>
										<LI>Frequency
											<UL>
												<LI>How much time passes between anniversaries (a month is exactly 30 days)</LI>
											</UL>
										</LI>
										<LI>From when
											<UL>
												<LI>Requalify on the anniversary of the <?= $settings->profile; ?>'s 'Hired Date' or the 'Anniversary' of a date of your choosing</LI>
											</UL>
										</LI>
										<LI>Prroducts
											<UL>
												<LI>Which products have to be filled out on requalification</LI>
											</UL>
										</LI>
										<LI><?= $settings->profile; ?>s
											<UL>
												<LI>How many <?= $settings->profile; ?>s have requalification enabled / How many <?= $settings->profile; ?>s this <?= $settings->client; ?> has</LI>
												<LI>Clicking this column will show the list of all <?= $settings->profile; ?> with requalification enabled for that <?= $settings->client; ?></LI>
											</UL>
										</LI>
										<LI>Click "Save Changes" to apply any changes</LI>
									</UL>
								</LI>
								<LI><?= $settings->profile; ?>s for [<?= $settings->client; ?>] with requalification enabled
									<UL>
										<LI>Rather than make you edit each <?= $settings->profile; ?>'s settings requalification individually, they have been placed here</LI>
										<LI>ID</LI>
										<LI>Name
											<UL>
												<LI>Clicking this view the <?= $settings->profile; ?></LI>
											</UL>
										</LI>
										<LI><?= $settings->profile; ?> type
											<UL>
												<LI>Lets you change the <?= $settings->profile; ?> type</LI>
												<LI>Types with a ? cannot have orders placed for them.  Types with a ? can.</LI>
											</UL>
										</LI>
										<LI>Expiry Date ?= [Today's Date]
											<UL>
												<LI>Lets you change the driver's license expiry date</LI>
												<LI>? = expired (bad), ? = not expired (good)</LI>
											</UL>
										</LI>
										<LI>IH
											<UL>
												<LI>Let's you change the <?= $settings->profile; ?>'s "Is Hired" setting</LI>
											</UL>
										</LI>
										<LI>Hired Date, Auto-Change
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
											<UL>
												<LI>The date the CRON event will run</LI>
											</UL>
										</LI>
										<LI><?= $settings->client; ?>
											<UL>
												<LI>Clicking this will view the <?= $settings->client; ?></LI>
											</UL>
										</LI>
										<LI>Requalified <?= $settings->profile; ?>
											<UL>
												<LI>Clicking this will view the <?= $settings->profile; ?></LI>
											</UL>
										</LI>
										<LI>Hired Date
											<UL>
												<LI>The <?= $settings->profile; ?>'s "Hired Date"</LI>
											</UL>
										</LI>
										<LI>Status
											<UL>
												<LI>The status of the CRON event, and what products it will send (if applicable)</LI>
											</UL>
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
									<UL>
										<LI>A 2 year calendar showing all CRON events. Hover over the grey squares to see the events for that day</LI>
									</UL>
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
											<UL>
												<LI>%event% will be replaced with the name of the email event being sent (For debugging)</LI>
											</UL>
										</LI>
										<LI>%variables%
											<UL>
												<LI>%variables% will be replaced with a list of all variables being injected/substituted (For debugging)</LI>
											</UL>
										</LI>
										<LI>%webroot%
											<UL>
												<LI>%webroot% will be replaced with <?= $this->request->webroot; ?></LI>
											</UL>
										</LI>
										<LI>%created%
											<UL>
												<LI>%created% will be replaced with the current date/time</LI>
											</UL>
										</LI>
										<LI>%login%
											<UL>
												<LI>%login% will be replaced with <?= LOGIN; ?></LI>
											</UL>
										</LI>
										<LI>%site%
											<UL>
												<LI>%site% will be replaced with <?= $settings->mee; ?></LI>
											</UL>
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
								<LI>Acronym</LI>
								<LI>Panel Color</LI>
								<LI>Button Color</LI>
								<LI>Checked</LI>
								<LI>Visible</LI>
								<LI>Bypass</LI>
								<LI>Sidebar Alias</LI>
								<LI>Blocks Alias</LI>
								<LI>Make Column</LI>
								<LI><?= $languages; ?> Name and Description</LI>
								<LI>Top Block Color</LI>
								<LI>Price</LI>
								<LI>Icon</LI>
								<LI>Show only these packages</LI>
								<LI>Product/<?= $settings->document; ?> IDs</LI>
								<LI>Show for <?= $settings->profile; ?> types</LI>
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

	<li id="thesidebar">The sidebar: (along the left)
		<ul>
			<LI><?= $settings->document; ?> Search...
				<UL>
					<LI>A shortcut to <SPAN ONCLICK="expand('documents/listdocuments');"><?= $strings["index_listdocuments"]; ?></SPAN> searching for the text you specify</LI>
				</UL>
			</LI>
			<LI><?= $strings["dashboard_dashboard"]; ?>
				<UL>
					<LI>This is the main/home page that will show shortcuts (top blocks) to various sections and documents as well as a list of clients. If you do not have the appropriate permissions, you may not see this screen, and instead will be redirected to a section you do have permissions for.</LI>
				</UL>
			</LI>
			<LI>
				<?= $settings->client; ?>
				<UL>
					<LI>
						<?= $strings["index_listclients"]; ?>
					</LI>
					<LI><?= $strings["index_createclients"]; ?>
						<UL>
							<LI><?= $strings["clients_addeditimage"]; ?></LI>
							<LI><?= $strings["index_listprofile"]; ?>
								<UL>
									<LI>Links to the <SPAN ONCLICK="expand('thesidebar/profiles/list-profiles');"><?= $strings["index_listprofile"]; ?></SPAN> section searching for <?= $settings->profile; ?>s assigned to this <?= $settings->client; ?></LI>
								</UL>
							</LI>
							<LI>Edit/View
								<UL>
									<LI>Switch between edit and view mode</LI>
								</UL>
							</LI>
							<LI>Delete
								<UL>
									<LI>Delete this <?= $settings->client; ?></LI>
								</UL>
							</LI>
							<LI>Info
								<UL>
									<LI>Lets you edit the data for this <?= $settings->client; ?></LI>
								</UL>
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
										<UL>
											<LI>Sets whether or not this document shows in the clientapplication process</LI>
										</UL>
									</LI>
									<LI>Display Order
										<UL>
											<LI>The display order can be changed only by clicking and dragging the row to a new position</LI>
										</UL>
									</LI>
									<LI>Click "Save Changes" to apply the changes</LI>
								</UL>
							</LI>
							<LI>Assign to <?= $settings->profile; ?>
								<UL>
									<LI>Lets you search for <?= $settings->profile; ?>s to assign to this <?= $settings->client; ?></LI>
									<LI>Changes are saved as you make them</LI>
								</UL>
							</LI>
						</UL>
					</LI>
				</UL>
			</LI>
			<LI id="profiles">
				<?= $settings->profile; ?>s
				<UL>
					<LI id="list-profiles"><?= $strings["index_listprofile"]; ?></LI>
					<LI id="profile"><?= $strings["index_createprofile"]; ?>

						<UL>
							<LI><?= $strings["clients_addeditimage"]; ?></LI>
							<LI><?= $strings["clients_enablerequalify"]; ?></LI>
							<LI><?= $strings["profiles_washired"]; ?></LI>
							<LI><?= $OrderTypes; ?></LI>
							<LI>View/Edit
								<UL>
									<LI>Switch between view and edit mode</LI>
								</UL>
							</LI>
							<LI>Delete
								<UL>
									<LI>Delete this profile</LI>
								</UL>
							</LI>
							<?php if($IsSuper){ ?>
								<LI>Possess
									<UL>
										<LI>Click this to log in as this user, for the purpose of testing</LI>
									</UL>
								</LI>
							<?php } ?>
							<LI><?= $strings["orders_scorecard"]; ?></LI>
							<LI id="profile-info"><?= $settings->profile; ?>
								<UL>
									<LI>Lets you edit the data for this profile</LI>
									<LI>Assign to <?= $settings->client; ?>
										<UL>
											<LI>A non-super <?= $settings->profile; ?> can only be assigned to a single <?= $settings->client; ?></LI>
										</UL>
									</LI>
								</UL>
							</LI>
							<LI id="permissions">Permissions
								<UL>
									<LI>Configuration
										<UL>
											<LI>Select All
												<UL>
													<LI>Checks all checkboxes automatically</LI>
												</UL>
											</LI>
											<LI>Change all existing <?= $settings->profile; ?>s of this type
												<UL>
													<LI>Once you click save, all <?= $settings->profile; ?>s of the same <?= $settings->profile; ?> type will have their permissions over-written with this <?= $settings->profile; ?>'s permissions</LI>
												</UL>
											</LI>
											<LI>Change all future <?= $settings->profile; ?>s of this type
												<UL>
													<LI>Once you click save, all <?= $settings->profile; ?>s of the same <?= $settings->profile; ?> type you make afterwards will start with the same permissions as this <?= $settings->profile; ?>'s</LI>
												</UL>
											</LI>
											<LI>Enable <?= $settings->profile; ?>
												<UL>
													<LI>Yes/No</LI>
													<LI>List</LI>
													<LI>Create</LI>
													<LI>Edit</LI>
													<LI>Delete</LI>
													<LI>Receive Email (on create <?= $settings->profile; ?>)</LI>
													<LI><?= $ProfileTypes; ?></LI>
												</UL>
											</LI>
											<LI>Enable <?= $settings->client; ?>
												<UL>
													<LI>Yes/No</LI>
													<LI>List</LI>
													<LI>Create</LI>
													<LI>Edit</LI>
													<LI>Delete</LI>
													<LI><?= $ClientTypes; ?></LI>
												</UL>
											</LI>
											<LI>Enable <?= $strings["index_orders"]; ?>
												<UL>
													<LI>Yes/No</LI>
													<LI>List</LI>
													<LI>Create</LI>
													<LI>Edit</LI>
													<LI>Delete</LI>
													<LI>Receive Email (on create <?= $strings["index_orders"]; ?>)</LI>
													<LI>Receive Email (on client application completion)</LI>
													<LI><?= $OrderTypes; ?></LI>
												</UL>
											</LI>
											<LI>Enable <?= $settings->document; ?>
												<UL>
													<LI>Yes/No</LI>
													<LI>List</LI>
													<LI>Create</LI>
													<LI>Edit</LI>
													<LI>Delete</LI>
													<LI>Receive Email (on create <?= $settings->document; ?>)</LI>
													<LI><?= $DocumentTypes; ?>
														<UL>
															<LI>None</LI>
															<LI>View Only</LI>
															<LI>Create Only</LI>
															<LI>Both</LI>
														</UL>
													</LI>
												</UL>
											</LI>
											<LI>Enable <?= $strings["index_tasks"]; ?></LI>
											<LI>Enable <?= $strings["index_addtasks"]; ?></LI>
											<LI>Enable <?= $strings["index_analytics"]; ?></LI>
											<LI>Enable <?= $strings["index_training"]; ?></LI>
											<LI>Enable Show Logo</LI>
										</UL>
									</LI>
									<LI>Top blocks
										<UL>
											<LI>Add a <?= $settings->profile; ?></LI>
											<LI><?= $strings["index_listprofile"]; ?></LI>
											<LI>Add a <?= $settings->client; ?></LI>
											<LI><?= $strings["index_listclients"]; ?></LI>
											<LI>Submit <?= $settings->document; ?></LI>
											<LI><?= $strings["index_listdocuments"]; ?></LI>
											<LI><?= $strings["index_training"]; ?></LI>
											<LI><?= $OrderTypes; ?></LI>

											<LI><?= $strings["index_listorders"]; ?></LI>
											<LI><?= $strings["index_tasks"]; ?></LI>
											<LI><?= $strings["index_addtasks"]; ?></LI>
											<LI><?= $settings->document; ?>s Drafts</LI>
											<LI><?= $strings["index_orders"]; ?> Drafts</LI>
											<LI><?= $strings["index_analytics"]; ?></LI>
											<LI>Bulk Order</LI>
										</UL>
									</LI>
									<LI><?= $strings["forms_savechanges"]; ?>
										<UL>
											<LI>Click this to save your changes</LI>
										</UL>
									</LI>
								</UL>
							</LI>
							<LI>Notes
								<UL>
									<LI>Lets you add/edit/delete notes for a <?= $settings->profile; ?></LI>
								</UL>
							</LI>
							<LI>Message
								<UL>
									<LI>Lets you send an email to this <?= $settings->profile; ?></LI>
								</UL>
							</LI>
						</UL>
					</LI>
				</UL>
			</LI>
			<LI>
				<?= $strings["index_training"]; ?>
				<UL>
					<LI><?= $strings["index_courses"]; ?></LI>
					<LI><?= $strings["index_quizresults"];?></LI>
				</UL>
			</LI>
			<LI id="documents">
				<?= $settings->document; ?>
				<UL>
					<LI id="listdocuments"><?= $strings["index_listdocuments"]; ?>
						<UL>
							<LI>test</LI>
						</UL>
					</LI>
					<LI><?= $strings["index_createdocument"]; ?></LI>
				</UL>
			</LI>
			<LI>
				<?= $strings["index_orders"]; ?>
				<UL>
					<LI><?= $strings["index_listorders"]; ?></LI>
					<LI><?= $OrderTypes; ?>
						<UL>
							<LI>A list of each available PRODUCT-TYPE that you can place an order for</LI>
						</UL>
					</LI>
				</UL>
			</LI>
			<LI>
				<?= $strings["index_analytics"]; ?>
				<UL>
					<LI>Allows you to view statistics on user activity between 2 dates using the datepickers at the top right. Defaults to the last 2 weeks.</LI>
				</UL>
			</LI>
			<LI>
				<?= $strings["index_tasks"]; ?>
				<UL>
					<LI><?= $strings["index_calendar"]; ?></LI>
					<LI><?= $strings["index_addtasks"]; ?></LI>
				</UL>
			</LI>
		</ul>
	</li>

	<li>The content: (the majority of the middle-right side)
	  <ul>
		<li>This is where the page you're on will be shown</li>
		<li>The arrow that sometimes appears at the bottom-right corner of this section will scroll you back to the top of the page</li>
	  </ul>
	</li>

	<LI class="white">SPACER</LI>

  </ul>
</div>
<BUTTON ONCLICK="ExpandAll();">Expand All</BUTTON>
<BUTTON ONCLICK="CollapseAll();">Collapse All</BUTTON>