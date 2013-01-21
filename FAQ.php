<?php
include('includes/haut.php');
$count = 0;
?>
<table>
	<h2>Help</h2>

	<tr>
		<td class="no_border"><button onClick = "showHideDetails('<?php echo $count++; ?>')">+</button>Why do I have to register to use Nemaid ?<br /></td>
	</tr>
	<tr class="details">
		<td class="no_border">
			<table>
				<tr >
					<td>
						NEMAID is free but
						you need to open an account in order to enter and save your
						data. This allows you to come back later and do some more work
						on a sample, possibly after changing some of the parameters. It
						also makes it possible for you to enter and save several
						populations and compare them to one another.
					<br />
					</td>
				</tr>
			</table>
			<br />
		</td>
	</tr>
	
<!----------------------------------------------------------------------->

<tr>
		<td class="no_border"><button onClick = "showHideDetails('<?php echo $count++; ?>')">+</button>How do I do a straightforward identification ?<br /></td>
	</tr>
	<tr class="details">
		<td class="no_border">
			<table>
				<tr >
					<td>
						To identify one of your unknown populations while accepting the various default options, do the 						following:
<p class="alinea1"> 1- Select the genus corresponding to your unknown population.</p>
<p class="alinea1"> 2 - Open the tab Data entry, then:</p>
<p class="alinea2">First, describe the origin of the population you want to identify. Click on "Enter label" and fill up at least the first line <strong>"Sample number"</strong>; the other lines are optional.
Then, click on "Characters" and enter your sample data. Use means for quantitative characters. For qualitative characters enter the percentage of specimens with each state of the character, using digital percentages (e.g., 0.73 instead of 73%).</p>
<p class="alinea1"> 3 - Open the tab "Results". Click on Compute <u>Coefficients of similarity</u> to see the list of species the most similar to your population. Each species is represented at least by its original description and, if it has been re-described at least once, by a composite description.</p>
<p class="alinea1"> 4 - Click on the name of a species (original or composite description) to see the list of characters used by the program to calculate the coefficient of similarity between your sample and that species.</p>
<br />
NEMAID can't help you any further. You must now check the published descriptions of the species at the top of the list and decide if your population belongs in one of them.
					<br />
					</td>
				</tr>
			</table>
			<br />
		</td>
	</tr>
	
<!----------------------------------------------------------------------->

<tr>
		<td class="no_border"><button onClick = "showHideDetails('<?php echo $count++; ?>')">+</button>Why are several species given as 100% similar to my sample ?<br /></td>
	</tr>
	<tr class="details">
		<td class="no_border">
			<table>
				<tr >
					<td>
						Unlike dichotomous keys, NEMAID does not give <u>one</u> answer, but a list of possible answers. Depending on the data you entered and the data in the database, NEMAID computes the similarity between your population and all of the species you compared it with. Obviously, the species you are looking for is not one at the bottom of the list. However, your population may be 100% similar to a species according to the characters used in NEMAID but different according to other characters, not used in the Nemaid computations. Therefore, you must look at the complete published descriptions, and possibly at type specimens, before making a final identification.
					<br />
					</td>
				</tr>
			</table>
			<br />
		</td>
	</tr>
	
<!----------------------------------------------------------------------->

<tr>
		<td class="no_border"><button onClick = "showHideDetails('<?php echo $count++; ?>')">+</button>What are the default options and how can I change them ?<br /></td>
	</tr>
	<tr class="details">
		<td class="no_border">
			<table>
				<tr >
					<td>
<u>Valid / invalid species/descriptions</u><br />
<span class="alinea1"> By default, Nemaid similarity coefficients are computed only for valid species and, within valid species, only for descriptions that have been accepted as truly representing the species to which they were ascribed. However, you are free to include also the species and populations that are considered as invalid (synonymy, species inquirendae, species transferred to another genus, etc) by later authors. <br /></span>
<span class="alinea1">In the tab <b>Perform a comparison</b>, check the box "Include invalid species".<br /></span>
<br />

<u>Type population, composite descriptions and individual populations</u><br />
<span class="alinea1"> By default, Nemaid similarity coefficients are computed for the type population and for a composite description of each species. However, you are free to consider only the type populations (original description), only the composite descriptions, or you can also ask the program to treat individually every description included in the table. In such a case, if species x is represented by, for example, six populations in the data table (its type population and 5 other populations), Nemaid will compute six coefficients of similarity between your sample and species x, one for each of these populations. <br /></span>
<span class="alinea1">In the tab <b>Perform a comparison</b>, Type of description, check the radio button of the option you want.<br /></span>
<br />

<u>Computation formulae</u><br />
<span class="alinea1"> By default, Nemaid similarity coefficients are computed using a new formula, different from the old Nemaid 1 and 2 formulae, and different also from the formulae used in version 3.0. The old Nemaid 1 and 2 formulae are gone for good, but you can ask the program to use the version 3.0 formulae.<br /></span>
<span class="alinea1">In the tab <b>Perform a comparison</b>, …??
[Team: We'll have to see how and where in the interface the users can select the computation formula (3.0 or 3.1)]<br /></span>
<br />

<u>Correction factors and weights</u><br />
<span class="alinea1"> All computation formulae include correction factors and weights for the various characters. Correction factors integrate the variability that can be attributed to each character, as proposed by various authors. Weights relate to the fact that some characters are more reliable than others for identification.</br>
You will find more details below in the dedicated Help sections.</br>
 You can see the correction factors and weights used by default in the formulae in the tab <b>"Your samples".</b>
</br> [Team: I'll finish that when we decide on the final interface]<br /></span>
<br />
					<br />
					</td>
				</tr>
			</table>
			<br />
		</td>
	</tr>
	
<!----------------------------------------------------------------------->
	<tr>
	<td class="no_border"><button onClick = "showHideDetails('<?php echo $count++; ?>')">+</button>What computation formulae are used in version 3.0 ?<br /></td>
	</tr>
	<tr class="details">
		<td class="no_border">
			<table>
				<tr >
					<td colspan="3">
						The formulae used in Nemaid 3.0 for computing similarity between your sample and the species in the genus you selected was the one described in <a href="../Fortuner_Wong_1985.pdf">Fortuner &amp; Wong,1985</a>, and particularly in the general flowchart of the program page 4 of the PDF.<br /><br />
						<i><b>Missing values</b></i><br />
						When the value of one of the characters is missing either for the sample or for the species, this character is neutralized for the computation of similarity between the sample and that species. <br /> (if missing value, then Nemaid sets score i = 0 and weight i = 0)<br /><br />
						<i><b>Numerical  characters :</b></i>
						see page 2 of the PDF above <br />
						Nemaid computes score S<sub>i</sub> for each numerical character i, as follows:
					<p class="MsoNormal" style=""><span style="" lang="EN-US">&nbsp;</span></p>
					<p class="MsoNormal" style="text-indent: 35.4pt;"><span style=""
						lang="EN-US">&nbsp;&nbsp;&nbsp;&nbsp; | Mx<sub>i</sub> &#8211; Ms<sub>i</sub> | - C<sub>i</sub></span></p>
					<p class="MsoNormal"><span style="" lang="EN-US">S<sub>i</sub> = 1 &#8211;<span
						  style="">&nbsp;&nbsp; </span>&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;</span></p>
					<p class="MsoNormal" style="text-indent: 35.4pt;"><span style=""
						lang="EN-US"><span style="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

						</span>R<sub>i</sub> - C<sub>i</sub></span></p>with
						<br />
						S<sub>i</sub> = score of the quantitative character i<br />
						Mx<sub>i</sub> = value of character i in the sample<br />
						Ms<sub>i</sub> = value of character i in a species S<span style="">&nbsp; </span><br />
						C<sub>i</sub> = correction factor<br />
						R<sub>i</sub> = range: [Highest value of i in all of the species in the
						genus] -&nbsp; [lowest value of i in all of the species in the
						genus] </span></p>
						<br />

						<i><b>Qualitative characters</b></i> : (Modified according to <a href="../Fortuner_1986d.pdf">Fortuner, 1986</a>).<br />
						Qualitative characters have 1 or more states. In each description, each character state is represented by the percentage of specimens having that state in the population. When a species has been described from 2 or more populations, the percentage values may vary in the various populations. Therefore, there are a maximum percentage and a minimum percentage. The percentages are always entered as decimal values, e.g., 0.85 instead of 85%.<br /><br />
						Nemaid computes a score S<sub>i</sub> for each qualitative character i,
						as follows:<br /><br />
						<span class="alinea1" >For each state s of each qualitative character, Nemaid computes the mid-range Ps and the half-range ps as follows:<br />
						<span class="alinea2" >Ps= [maximum % in the various population of the species + minimum % in same] / 2<br /></span>
						<span class="alinea2" >ps = [maximum % - minimum %] / 2<br /></span>
						<span class="alinea1" >Then, Nemaid computes the score for each character state Ss<br /></span>
						<span class="alinea2" >Ss = 1 &#8211; (|U-Ps| - ps)<br /></span>
						<span class="alinea2" >with U = % of specimens with that character state in the sample<br /></span>
						<span class="alinea2" >Finally, Nemaid average the successive Ss to obtain the score Si of the qualitative character</span><br /><br />
						<i><b>Weighting the scores</b></i><br />
						Nemaid multiplies each character score Si by the character weight Wi<br /><br />
						<i><b>Final coefficient of similarity</b></i><br />
						<span style="" lang="EN-US">S = </span><span
						style="font-family: Symbol;" lang="EN-US"><span style="">&Sigma;</span></span><span
						style="" lang="EN-US"> S<sub>i</sub> W<sub>i</sub> / </span><span
						style="font-family: Symbol;" lang="EN-US"><span style="">&Sigma;</span></span><span
						style="" lang="EN-US"> W<sub>i
						</sub></span>
					</td>
				</tr>
			</table>
		</td>
	</tr>
<!----------------------------------------------------------------------->

<tr>
	<td class="no_border"><button onClick = "showHideDetails('<?php echo $count++; ?>')">+</button>What computation formulae are used in version 3.1 ?<br /></td>
	</tr>
	<tr class="details">
		<td class="no_border">
			<table>
				<tr >
					<td colspan="3">
						In fact, Nemaid 3.1 uses a single formula that applies to quantitative and qualitative characters alike.<br /><br />
For each population in the species database, quantitative characters are given by the population mean and qualitative characters by the decimal percentage of specimens presenting each state of the character in the population.<br /><br />
Every species is represented by its type population and, when it has been re-described in one or several articles, by its composite description where the value of each character and character state is the mean of that character and state in the various populations of that species that have been described by various authors over the years.<br /><br />
						
						When the value of one of the characters is missing either for the sample or for the species, this character is neutralized for the computation of similarity between the sample and that species. <br /> (if missing value, then Nemaid sets score i = 0 and weight i = 0)<br /><br />

Formula 3.1 compares separately the values for each character I and character state ie :<br /><br />
For character i, state e, compute:<br />
					<p class="MsoNormal" style=""><span style="" lang="EN-US">&nbsp;</span></p>
					<p class="MsoNormal" style="text-indent: 35.4pt;"><span style=""
						lang="EN-US">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; | Mx<sub>ie</sub> &#8211; Ms<sub>ie</sub> | - C<sub>i</sub></span></p>
					<p class="MsoNormal"><span style="" lang="EN-US">S<sub>ie</sub> = 1 &#8211;<span
						  style="">&nbsp; </span>&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;</span></p>
					<p class="MsoNormal" style="text-indent: 35.4pt;"><span style=""
						lang="EN-US"><span style="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

						</span>R<sub>ie</sub> - C<sub>i</sub></span></p>with
						<br />
						S<sub>ie</sub> = score of the character i, state e<br />
						Mx<sub>ie</sub> = value of character i, state e in the sample<br />
						Ms<sub>ie</sub> = value of character i, state e in a species S (type or composite description)<span style="">&nbsp; </span><br />
						C<sub>i</sub> = correction factor for character i (there is only one correction factor per character, even for characters with 2 or more states)<br />
						R<sub>ie</sub> = range of the values of character i, state e in the whole genus (maximum value of ie minus minimum value of ie) </span></p><br />

						
For the type population, Ms<sub>ie</sub> is equal to the value given in line T (this line includes only one value for each state of each character).<br />
For the composite populations, Ms<sub>ie</sub> is equal to the mean of the values entered in the various populations described under "Species S".<br /><br />

S<sub>ie</sub> is computed for each state e of each character i. Many characters (all the measurements and the "presence/absence" characters) have only 1 state. The others have 2, 3, 4 or possibly more states.<br /><br />

When a character has more than one state, the character score S<sub>1</sub> is computed by averaging the state values (1, 2, 3, 4 or possibly more values).<br /><br />
						
						<i><b>Weighting the scores</b></i><br />
						Nemaid multiplies each character score S<sub>i</sub> by the character weight W<sub>i</sub>.<br /><br />
						<i><b>Final coefficient of similarity</b></i><br />
						<span style="" lang="EN-US">S = </span><span
						style="font-family: Symbol;" lang="EN-US"><span style="">&Sigma;</span></span><span
						style="" lang="EN-US"> S<sub>i</sub> W<sub>i</sub> / </span><span
						style="font-family: Symbol;" lang="EN-US"><span style="">&Sigma;</span></span><span
						style="" lang="EN-US"> W<sub>i
						</sub></span>
						
					</td>
				</tr>
			</table>
		</td>
	</tr>

<!----------------------------------------------------------------------->

<tr>
		<td class="no_border"><button onClick = "showHideDetails('<?php echo $count++; ?>')">+</button>What are the correction factors included in the Nemaid formulae ?<br /></td>
	</tr>
	<tr class="details">
		<td class="no_border">
			<table>
				<tr >
					<td>
						Nemaid integrates the variability observed for some characters among the species of any given genus. For example, in Helicotylenchus, it has been observed that the body length can vary by up to 150 µm when the progeny of a single parthenogenetic female are raised on different hosts plants (<a href="http://genisys.prd.fr/Fortuner_1984c.pdf">Fortuner, 1984</a>). In the Nemaid formulae, the specific variability is represented by corrections factors and a difference in measurements or percentages between an unknown sample and a species is accepted as indicating a dissimilarity only in so far that such a difference is larger than the correction factor of the corresponding character. <br/> If you don’t agree with the default values displayed in the tab “Your samples”, you are free to modify them.
					<br />
					</td>
				</tr>
			</table>
			<br />
		</td>
	</tr>

<!----------------------------------------------------------------------->

<tr>
		<td class="no_border"><button onClick = "showHideDetails('<?php echo $count++; ?>')">+</button>What are the weights included in the Nemaid formulae ?<br /></td>
	</tr>
	<tr class="details">
		<td class="no_border">
			<table>
				<tr >
					<td>
						It seems obvious that some characters are more reliable than others. For example, it is generally very easy to measure the body length of a nematode whereas the orifice of the dorsal esophageal gland is often obscure, which means that total body length is more reliable than DGO. In the Nemaid formulae, each character is weighted according to its reliability.</br>
If you don’t agree with the default values displayed in the tab “Your samples”, you are free to modify them. You can also chose to set all weights equal to 1, in which case the characters will no longer be weighted.
					<br />
					</td>
				</tr>
			</table>
			<br />
		</td>
	</tr>

<!----------------------------------------------------------------------->

<tr>
		<td class="no_border"><button onClick = "showHideDetails('<?php echo $count++; ?>')">+</button>What are the ranges included in the Nemaid formulae and why can't I change it ?<br /></td>
	</tr>
	<tr class="details">
		<td class="no_border">
			<table>
				<tr >
					<td>
						A character range is the difference between the maximum and minimum values found in the data table for the selected genus. For example, in Helicotylenchus, the largest species (<i>H. coomansi</i>) has a body length of 1235 µm while the smallest species (<i>H. minutus</i>) is 400 µm long. In that genus, the range for body length is 1235-400 = 835 µm. Stylets are far shorter than the whole body and the range for stylet length is only 27 µm. It is even smaller (2) for ratio <b>c’</b>. Ranges are included in the Nemaid formulae in order to compare differences in characters with absolute values so widely different.</br>
Ranges are objective values, computed by the program directly from the data in the data table for the selected genus and they cannot be modified by the users.
					<br />
					</td>
				</tr>
			</table>
			<br />
		</td>
	</tr>

<!----------------------------------------------------------------------->

<tr>
		<td class="no_border"><button onClick = "showHideDetails('<?php echo $count++; ?>')">+</button>What is a composite description ?<br /></td>
	</tr>
	<tr class="details">
		<td class="no_border">
			<table>
				<tr >
					<td>
						In the database with the descriptions of the species in a genus included in Nemaid, every species is represented at least by its type population (original description). When a species has been redescribed in one or several articles, the program computes its composite description where the value of each character and character state is the mean of that character and state in the various populations of that species as described by various authors over the years.
					<br />
					</td>
				</tr>
			</table>
			<br />
		</td>
	</tr>
	
<!----------------------------------------------------------------------->
	<tr>
		<td class="no_border"><button onClick = "showHideDetails('<?php echo $count++; ?>')">+</button>Why are composite descriptions missing for some species when I select the option "original and composite description" ?<br /></td>
	</tr>
	<tr class="details">
		<td class="no_border">
			<table>
				<tr >
					<td>
						Composite descriptions are missing for those species that have been described only once (original description) and have not been described by later authors.
					<br />
					</td>
				</tr>
			</table>
			<br />
		</td>
	</tr>

<!----------------------------------------------------------------------->
	<tr>
	<td class="no_border"><button onClick = "showHideDetails('<?php echo $count++; ?>')">+</button>What's the meaning of the codes used in the column "Authors" ?<br /></td>
	</tr>
	<tr class="details">
		<td class="no_border">
			<table border='2'>
				<tr >
					<td>Code</td>
					<td>Example</td>
					<td>Meaning</td>
				</tr>
				<tr>
					<td>Parentheses</td>
					<td> [ Luc, 1960 ]</td>
					<td>Species originally described under a different genus name</td>
				</tr>
				<tr>
					<td>in</td>
					<td> in Van den Berg &amp; Heyns, 1975</td>
					<td>Description of non-type material of a known species.</td>
				</tr>
				<tr>
					<td>Sign +</td>
					<td>Sher, 1966 + Fortuner et al, 1981</td>
					<td>Combination of the description by one author (here Sher) and the redescription of the same specimens by a later author (here Fortuner et al.)</td>
				</tr>
				<tr>
					<td>Topotypes</td>
					<td>topotypes in Sher, 1966</td>
					<td>Description of type material other than holotype/paratypes</td>
				</tr>
				<tr>
					<td>Quote marks</td>
					<td>"Sagitov et al., 1978"</td>
					<td>Species originally described under a different species name and later renamed for nomenclatural reasons. The original name is indicated in column 6</td>
				</tr>
			</table>
			<br />
		</td>
	</tr>
	
<!----------------------------------------------------------------------->


	<tr>
		<td class="no_border"><button onClick = "showHideDetails('<?php echo $count++; ?>')">+</button>Will there be more genera available ?<br /></td>
	</tr>
	<tr class="details">
		<td class="no_border">
			<table>
				<tr >
					<td>
					That depends on you
					and the other users! If you want a particular genus to be
					entered in the NEMAID list of available genera, please send a
					message to fortuner@wanadoo.fr.
					<br />
					</td>
				</tr>
			</table>
			<br />
		</td>
	</tr>


<!----------------------------------------------------------------------->

	<tr>
		<td class="no_border"><button onClick = "showHideDetails('<?php echo $count++; ?>')">+</button>How do I report an error in the description database ?<br /></td>
	</tr>
	<tr class="details">
		<td class="no_border">
			<table>
				<tr >
					<td>
					Please send a message to fortuner@wanadoo.fr with the exact description of the error,
					including name of the species and description involved, name of
					the erroneous character, and article where the correct data can
					be found.
					</td>
				</tr>
			</table>
			<br />
		</td>
	</tr>

			
	
</table>
