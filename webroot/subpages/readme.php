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

	#listContainer SPAN{
		color: blue;
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

	$('#expandList')
			.unbind('click')
			.click( function() {
				$('.collapsed').addClass('expanded');
				$('.collapsed').children().show('medium');
			})
	$('#collapseList')
			.unbind('click')
			.click( function() {
				$('.collapsed').removeClass('expanded');
				$('.collapsed').children().hide('medium');
			})

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
</SCRIPT>
<?php
	$settings = $Manager->get_settings();
	$language = "English";
	$strings = CacheTranslations($language, array("clients_%", "profiles_washired", "orders_scorecard"), $settings);

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
<P>
Clicking a section of this page will expand it to show more information about it
<P>
There are 4 parts to the Veritas screen
<div id="listContainer">
  <ul id="expList">
	<li id="theheader">The header: (along the top)
		<ul>
			<li>The right side contains a dropdown menu allowing you to access <SPAN ONCLICK="expand('theheader/your-settings');"><?= $strings["dashboard_mysettings"]; ?></SPAN>, switch languages, and logout</li>
			<LI ID="your-settings"><?= $strings["dashboard_mysettings"]; ?>
				<UL>
					<LI ONCLICK="expand('profile/profile-info');"><?= $settings->profile ?></LI>
					<LI ONCLICK="expand('profile/permissions');">Permissions</LI>
				</UL>
			</LI>
		</ul>
	</li>

	<li id="thefooter">The footer: (along the bottom)
		<ul>
			<li>Some pages will show a list of checkboxes on the left side to indicate what permissions they use (visible when you hover your mouse over the checkbox) and if you have them enabled</li>
			<li>On the right side is a list of links to various pages (<?= $titles; ?>) which can be customized in the <SPAN ONCLICK="expand('system-settings/pages');">(system) settings</SPAN> page</li>
			<li>If you have the appropriate permissions, there will also be links to toggle Debug Mode, go to (system) settings, and view the Email Log file</li>
			<LI id="system-settings">(System) Settings
				<UL>
					<LI>Logos
						<UL>
							<LI>
								You can set Primary (the top of the sidebar), Secondary (bottom of the sidebar), Login (for the login screen), and Client (shown for clients that don't set their own logo) logos.
							</LI>
						</UL>
					</LI>
					<LI id="pages">Pages
						<UL>
							<LI>You can customize the titles and descriptions for the <?= $titles; ?> pages here</LI>
						</UL>
					</LI>
					<LI>Display</LI>
					<LI>Packages</LI>
					<LI>Configuration</LI>
					<LI>Clear Data</LI>
					<LI>All Crons</LI>
					<LI>Profile Importer</LI>
					<LI>Email Editor</LI>
					<LI>Translation</LI>
					<LI>Product Types</LI>
				</UL>
			</LI>
		</ul>
	</li>

	<li id="thesidebar">The sidebar: (along the left)
		<ul>
			<LI>What's visible here will be dependant on your permissions</LI>
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
					<LI>
						<?= $strings["index_createclients"]; ?>
					</LI>
				</UL>
			</LI>
			<LI>
				<?= $settings->profile; ?>s
				<UL>
					<LI><?= $strings["index_listprofile"]; ?></LI>
					<LI><?= $strings["index_createprofile"]; ?></LI>
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

  	<LI id="profile">View/Edit <?= $settings->profile; ?>
	  	<UL>
			<LI><?= $strings["clients_addeditimage"]; ?></LI>
			<LI><?= $strings["clients_enablerequalify"]; ?></LI>
			<LI><?= $strings["profiles_washired"]; ?></LI>
			<LI><?= $OrderTypes; ?></LI>
			<LI><?= $strings["orders_scorecard"]; ?></LI>
			<LI id="profile-info"><?= $settings->profile; ?></LI>
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
					<LI>Save Changes
						<UL>
							<LI>Click this to save your changes</LI>
						</UL>
					</LI>
				</UL>
			</LI>
			<LI>Notes</LI>
			<LI>Message</LI>
		</UL>
  	</LI>
  </ul>
</div>
<!-- Doesn't work: <div class="listControl"><a id="expandList"></a>Expand All <a id="collapseList"></a>Collapse All</div> -->