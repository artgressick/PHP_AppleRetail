<?
	include('_controller.php');

	function sitm() { 
		global $BF,$info;
?>

		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td width="50%"><?=form_text(array('caption'=>'Store Name/Store Number','name'=>'chrStoreName','value'=>'','display'=>'true'))?></td>
				<td width="50%"><?=form_text(array('caption'=>'Completed By','name'=>'chrUser','size'=>'30','display'=>'true','value'=>$_SESSION['chrFirst'].' '.$_SESSION['chrLast']))?></td>
			</tr>
			<tr>
				<td width="50%"><?=form_text(array('caption'=>'Date','name'=>'dDate','size'=>'20','value'=>date('m/d/Y'),'display'=>'true'))?></td>
				<td width="50%"><?=form_text(array('caption'=>'Mall Management Company','name'=>'chrMallManagement','size'=>'40','value'=>'','display'=>'true'))?></td>
			</tr>
			<tr>
				<td width="50%" style='vertical-align:top;'><?=form_text(array('caption'=>'Address','name'=>'chrAddress','size'=>'30','value'=>'123 Street Rd.','display'=>'true'))?>
								<?=form_text(array('caption'=>'Address2','nocaption'=>'true','name'=>'chrAddress2','size'=>'30','value'=>'Suite 123','display'=>'true'))?>
								<?=form_text(array('caption'=>'Address3','nocaption'=>'true','name'=>'chrAddress3','size'=>'30','value'=>'Room 101','display'=>'true'))?>
								<?=form_text(array('caption'=>'City','name'=>'chrCity','size'=>'30','value'=>'City','display'=>'true'))?>
				</td>
				<td width="50%" style='vertical-align:top;'>
								<?=form_text(array('caption'=>'State','name'=>'chrState','size'=>'4','value'=>'ST','display'=>'true'))?>
								<?=form_text(array('caption'=>'Zip','name'=>'chrPostalCode','size'=>'10','value'=>'12345','display'=>'true'))?>
								<?=form_text(array('caption'=>'Country','name'=>'chrCountry','size'=>'30','value'=>'United States','display'=>'true'))?>
				
				</td>
			</tr>
		</table>
		<hr />		
		<div style="font-size:14px; font-weight:bold;">Receiving Information</div>
				
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td><?=form_text(array('caption'=>'Site specific delivery times','name'=>'chrDeliveryTimes','size'=>'40','value'=>'','display'=>'true'))?></td>
			</tr>
			<tr>
				<td><?=form_text(array('caption'=>'Truck Size Restrictions','name'=>'','size'=>'40','value'=>'','display'=>'true'))?></td>
			</tr>
			<tr>
				<td><?=form_text(array('caption'=>'Is there shelter in the event of rain or snow?','name'=>'bShelter','value'=>($info['bShelter']?'Yes':'No'),'display'=>'true'))?></td>
			</tr>
			<tr>
				<td style='padding-bottom:10px;border-top:1px dotted #666;'><div class='header4'>If your store has a public loading dock complete the following:</div><div>(Walk receiving area, include map denoting dock and store location, photos of the dock and path from the store)</td>
			</tr>
			<tr>
				<td><?=form_text(array('caption'=>'What loading dock is the closest to the store&#39;s backdoor?','name'=>'','size'=>'30','value'=>'','display'=>'true'))?></td>
			</tr>
			<tr>
				<td><?=form_text(array('caption'=>'Is it available for our use?','name'=>'bUseDock','value'=>($info['bUseDock']?'Yes':'No'),'display'=>'true'))?></td>
			</tr>
			<tr>
				<td><?=form_text(array('caption'=>'Does the mall require us to schedule our delivery with them?','name'=>'bMallDelievey','value'=>($info['bMallDelivery']?'Yes':'No'),'display'=>'true'))?></td>
			</tr>
			<tr>
				<td><?=form_text(array('caption'=>'If so, provide contact information.','name'=>'','size'=>'40','value'=>'','display'=>'true'))?></td>
			</tr>
			<tr>
				<td><?=form_text(array('caption'=>'Is the loading area elevated?','name'=>'bDockElevated','value'=>($info['bDockElevated']?'Yes':'No'),'display'=>'true'))?></td>
			</tr>
			<tr>
				<td><?=form_text(array('caption'=>'Is there any common area/mall pass through to get to the store?','name'=>'bPassStore','value'=>($info['bPassStore']?'Yes':'No'),'display'=>'true'))?></td>
			</tr>
			<tr>
				<td><?=form_text(array('caption'=>'Details from the Dock to Store','name'=>'','required'=>'Include photos of path from loading dock to the store. Note any items unidentifiable on the mall map (e.g., narrow halls, small doorways, obstructions, freight elevators).','value'=>nl2br(''),'display'=>'true'))?></td>
			</tr>
			<tr>
				<td style='padding-bottom:10px;border-top:1px dotted #666;'><div class='header4'>If your store has a back door access complete the following:</div><div>(Walk receiving area, include photos of the receiving area and path to BOH/FOH)</td>
			</tr>
			<tr>
				<td><?=form_text(array('caption'=>'Is the loading area elevated?','name'=>'bDockElevated','value'=>($info['bDockElevated']?'Yes':'No'),'display'=>'true'))?></td>
			</tr>
			<tr>
				<td><?=form_text(array('caption'=>'Details from the Receiving Area to Store','name'=>'','required'=>'Include photos of the receiving area and path to BOH/FOH. Note any items unidentifiable on the mall map (e.g., narrow halls, small doorways, obstructions, freight elevators).','value'=>nl2br(''),'display'=>'true'))?></td>
			</tr>
			<tr>
				<td><?=form_text(array('caption'=>'Is there any specific equipment required?','required'=>'e.g., lift gate, pallet jack','name'=>'','size'=>'40','value'=>'','display'=>'true'))?></td>
			</tr>
			<tr>
				<td><?=form_text(array('caption'=>'Are there any mall restrictions?','required'=>'e.g., pallets with soft wheels','name'=>'','size'=>'40','value'=>'','display'=>'true'))?></td>
			</tr>
		</table>	
		<hr />
		<div style="font-size:14px; font-weight:bold;">Additional Information</div>
		
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td><?=form_text(array('caption'=>'Do we have access to cardboard Recycling and Trash near the receiving area?','name'=>'bTrash','value'=>($info['bTrash']?'Yes':'No'),'display'=>'true'))?></td>
			</tr>
			<tr>
				<td><?=form_text(array('caption'=>'If yes, is a key required?','required'=>'If Yes, Obtain a copy of the key prior to offload','name'=>'bTashKey','value'=>($info['bTrashKey']?'Yes':'No'),'display'=>'true'))?></td>
			</tr>	
			<tr>
				<td><?=form_text(array('caption'=>'Where in the mall is the store located?','name'=>'','size'=>'40','value'=>'','display'=>'true'))?></td>
			</tr>
			<tr>
				<td><?=form_text(array('caption'=>'What is the nearest landmark or anchor store near the loading dock?','name'=>'','size'=>'40','value'=>'','display'=>'true'))?></td>
			</tr>
			<tr>
				<td><?=form_text(array('caption'=>'What is the before and after hours mall entry route?','name'=>'','size'=>'40','value'=>'','display'=>'true'))?></td>
			</tr>
			<tr>
				<td><?=form_text(array('caption'=>'Where should employees park?','name'=>'','size'=>'40','value'=>'','display'=>'true'))?></td>
			</tr>
			<tr>
				<td><?=form_text(array('caption'=>'Food options within the mall?','name'=>'bFood','value'=>($info['bFood']?'Yes':'No'),'display'=>'true'))?></td>
			</tr>
			<tr>
				<td><?=form_text(array('caption'=>'What Airport? What surrounding Hotels?','name'=>'','value'=>nl2br(''),'display'=>'true'))?></td>
			</tr>
			<tr>
				<td>
					<div class='FormName'>Alarm Contact Information <span class='FormRequired'>(in order of how they should be called.)</span></div>
					<table cellpadding='0' cellspacing='5' border='0'>
						<tr>
							<td>&nbsp;</td>
							<td class='FormField'>Contact Name</td>
							<td class='FormDisplay'>Phone Number</td>
						</tr>
						<tr>
							<td class='FormField'>1.</td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'20','display'=>'true'))?></td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'10','display'=>'true'))?></td>
						</tr>
						<tr>
							<td class='FormField'>2.</td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'20','display'=>'true'))?></td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'10','display'=>'true'))?></td>
						</tr>
						<tr>
							<td class='FormField'>3.</td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'20','display'=>'true'))?></td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'10','display'=>'true'))?></td>
						</tr>
						<tr>
							<td class='FormField'>4.</td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'20','display'=>'true'))?></td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'10','display'=>'true'))?></td>
						</tr>
						<tr>
							<td class='FormField'>5.</td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'20','display'=>'true'))?></td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'10','display'=>'true'))?></td>
						</tr>
						<tr>
							<td class='FormField'>6.</td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'20','display'=>'true'))?></td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'10','display'=>'true'))?></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<div class='FormName'>First and Last Names of Attendee for NSO Conference Call</div>
					<table cellpadding='0' cellspacing='5' border='0'>
						<tr>
							<td>&nbsp;</td>
							<td class='FormField'>First Name</td>
							<td class='FormField'>Last Name</td>
						</tr>
						<tr>
							<td class='FormField'>1.</td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'20','display'=>'true'))?></td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'20','display'=>'true'))?></td>
						</tr>
						<tr>
							<td class='FormField'>2.</td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'20','display'=>'true'))?></td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'20','display'=>'true'))?></td>
						</tr>
						<tr>
							<td class='FormField'>3.</td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'20','display'=>'true'))?></td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'20','display'=>'true'))?></td>
						</tr>
						<tr>
							<td class='FormField'>4.</td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'20','display'=>'true'))?></td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'20','display'=>'true'))?></td>
						</tr>
						<tr>
							<td class='FormField'>5.</td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'20','display'=>'true'))?></td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'20','display'=>'true'))?></td>
						</tr>
						<tr>
							<td class='FormField'>6.</td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'20','display'=>'true'))?></td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'20','display'=>'true'))?></td>
						</tr>
						<tr>
							<td class='FormField'>7.</td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'20','display'=>'true'))?></td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'20','display'=>'true'))?></td>
						</tr>
						<tr>
							<td class='FormField'>8.</td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'20','display'=>'true'))?></td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'20','display'=>'true'))?></td>
						</tr>
						<tr>
							<td class='FormField'>9.</td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'20','display'=>'true'))?></td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'20','display'=>'true'))?></td>
						</tr>
						<tr>
							<td class='FormField'>10.</td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'20','display'=>'true'))?></td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'20','display'=>'true'))?></td>
						</tr>
						<tr>
							<td class='FormField'>11.</td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'20','display'=>'true'))?></td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'20','display'=>'true'))?></td>
						</tr>
						<tr>
							<td class='FormField'>12.</td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'20','display'=>'true'))?></td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'20','display'=>'true'))?></td>
						</tr>
						<tr>
							<td class='FormField'>13.</td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'20','display'=>'true'))?></td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'20','display'=>'true'))?></td>
						</tr>
						<tr>
							<td class='FormField'>14.</td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'20','display'=>'true'))?></td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'20','display'=>'true'))?></td>
						</tr>
						<tr>
							<td class='FormField'>15.</td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'20','display'=>'true'))?></td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'20','display'=>'true'))?></td>
						</tr>
						<tr>
							<td class='FormField'>16.</td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'20','display'=>'true'))?></td>
							<td><?=form_text(array('nocaption'=>'true','name'=>'','size'=>'20','display'=>'true'))?></td>
						</tr>
					</table>
				</td>
			</tr>



			<tr>
				<td><div class='FormName'>Mall hours</div></td>
			</tr>
			<tr>
				<td>
					<table border="0" cellpadding="3" cellspacing="0" style='border:1px solid #CCC;'>
						<tr>
							<td colspan='3' class='FormName' style='text-align:center; border-right:1px solid #CCC;'>
								Sunday
							</td>
							<td colspan='3' class='FormName' style='text-align:center; border-right:1px solid #CCC;'>
								Monday
							</td>
							<td colspan='3' class='FormName' style='text-align:center; border-right:1px solid #CCC;'>
								Tuesday
							</td>
							<td colspan='3' class='FormName' style='text-align:center; border-right:1px solid #CCC;'>
								Wednesday
							</td>
							<td colspan='3' class='FormName' style='text-align:center; border-right:1px solid #CCC;'>
								Thursday
							</td>
							<td colspan='3' class='FormName' style='text-align:center; border-right:1px solid #CCC;'>
								Friday
							</td>
							<td colspan='3' class='FormName' style='text-align:center; border-right:1px solid #CCC;'>
								Saturday
							</td>
						</tr>
						<tr>
							<td>
								<?=form_text(array('nocaption'=>'true','name'=>'','size'=>'4','display'=>'true'))?>
							</td>
							<td>to</td>
							<td style='border-right:1px solid #CCC;'>
								<?=form_text(array('nocaption'=>'true','name'=>'','size'=>'4','display'=>'true'))?>
							</td>
							<td>
								<?=form_text(array('nocaption'=>'true','name'=>'','size'=>'4','display'=>'true'))?>
							</td>
							<td>to</td>
							<td style='border-right:1px solid #CCC;'>
								<?=form_text(array('nocaption'=>'true','name'=>'','size'=>'4','display'=>'true'))?>
							</td>
							<td>
								<?=form_text(array('nocaption'=>'true','name'=>'','size'=>'4','display'=>'true'))?>
							</td>
							<td>to</td>
							<td style='border-right:1px solid #CCC;'>
								<?=form_text(array('nocaption'=>'true','name'=>'','size'=>'4','display'=>'true'))?>
							</td>
							<td>
								<?=form_text(array('nocaption'=>'true','name'=>'','size'=>'4','display'=>'true'))?>
							</td>
							<td>to</td>
							<td style='border-right:1px solid #CCC;'>
								<?=form_text(array('nocaption'=>'true','name'=>'','size'=>'4','display'=>'true'))?>
							</td>
							<td>
								<?=form_text(array('nocaption'=>'true','name'=>'','size'=>'4','display'=>'true'))?>
							</td>
							<td>to</td>
							<td style='border-right:1px solid #CCC;'>
								<?=form_text(array('nocaption'=>'true','name'=>'','size'=>'4','display'=>'true'))?>
							</td>
							<td>
								<?=form_text(array('nocaption'=>'true','name'=>'','size'=>'4','display'=>'true'))?>
							</td>
							<td>to</td>
							<td style='border-right:1px solid #CCC;'>
								<?=form_text(array('nocaption'=>'true','name'=>'','size'=>'4','display'=>'true'))?>
							</td>
							<td>
								<?=form_text(array('nocaption'=>'true','name'=>'','size'=>'4','display'=>'true'))?>
							</td>
							<td>to</td>
							<td style='border-right:1px solid #CCC;'>
								<?=form_text(array('nocaption'=>'true','name'=>'','size'=>'4','display'=>'true'))?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style='padding-top:10px;'><?=form_text(array('caption'=>'Any additional information that would be helpful for traveling business partners?','name'=>'','value'=>nl2br(''),'display'=>'true'))?></td>
			</tr>
		</table>
<?
	}
?>