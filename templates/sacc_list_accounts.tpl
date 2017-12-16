<h4><{$lang_pagetitle}></h4>
<!-- Set up list of accounts -->
<table width="100%" class="outer" cellspacing='1'>
<caption>
<{$lang_instruction}>
</caption>
  <tr>
    <th><{$lang_col1}></th>
    <th align='center'><{$lang_col2}></th>
    <th align='center'><{$lang_col3}></th>
    <th align='center'><{$lang_col4}></th>
    <th align='center'><{$lang_col5}></th>
    <th align='center'><{$lang_col6}></th>
    <th align='center'><{$lang_col6b}></th>
    <th align='center'><{$lang_col7}></th>
    <th align='center'>&nbsp;</th>
  </tr>

<!-- start sets item loop -->
<{section name=i loop=$accounts}>
  <tr>
    <td class="even"><{$accounts[i].id}></td>
    <td align="LEFT" class="odd"><{$accounts[i].ac_nm}></td>
    <td align="LEFT" class="even"><{$accounts[i].ac_curr}></td>
    <td align="LEFT" class="odd"><{$accounts[i].ac_prps}></td>
    <td align="RIGHT" class="even"><{$accounts[i].ac_dr}></td>
    <td align="RIGHT" class="odd"><{$accounts[i].ac_cr}></td>
    <td align="RIGHT" class="even"><{$accounts[i].ac_net_bal}></td>
    <td align="LEFT" class="odd"><{$accounts[i].row_flag}></td>
	<td align="CENTER" class="even"><a href="sacc_entry_list.php?org_id=<{$org_id}>&ac_id=<{$accounts[i].id}>"><img src="<{$xoops_url}>/modules/sacc/images/b_browse.png" title ="<{$lang_select}>"></a></td>
  </tr>
<{/section}>
<!-- end sets item loop -->

</table>

<!-- do not remove the following copyright section -->
<div align="right">
<br>SACC (c) 2004, <a href="http://akitson.bbcb.co.uk" title="Click to see software author's web page" target="_blank">A Kitson.</a> UK.
</div>
<!-- end of copyright section -->
