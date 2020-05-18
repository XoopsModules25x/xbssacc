<h4><{$lang_pagetitle}></h4>
<!-- Set up list of account entries -->
<table width="100%" class="outer" cellspacing='1'>
    <caption>
        <{$lang_table1name}><br>
        <!--
<a href="sacc_acc_edit.php?org_id=<{$org_id}>&ac_id=0"><img src="<{$xoops_url}>/modules/sacc/images/b_insrow.png" title ="<{$lang_insert}>"> <{$lang_insert}></a>&nbsp;&nbsp;
<{$lang_instruction}>
-->
    </caption>
    <tr>
        <th><{$lang_col1}></th>
        <th align='center'><{$lang_col2}></th>
        <th align='center'><{$lang_col3}></th>
        <th align='center'><{$lang_col4}></th>
        <th align='center'><{$lang_col5}></th>
        <th align='center'><{$lang_col6}></th>
        <th align='center'>&nbsp;</th>
    </tr>

    <!-- start sets item loop -->
    <{section name=i loop=$entries}>
        <tr>
            <td class="even"><{$entries[i].jrn_id}></td>
            <td align="LEFT" class="odd"><{$entries[i].txn_ref}></td>
            <td align="LEFT" class="even"><{$entries[i].row_flag}></td>
            <td align="RIGHT" class="odd"><{$entries[i].txn_dr}></td>
            <td align="RIGHT" class="even"><{$entries[i].txn_cr}></td>
            <!--
	<td align="CENTER" class="even"><a href="sacc_entry_list.php?org_id=<{$org_id}>&ac_id=<{$accounts[i].id}>"><img src="<{$xoops_url}>/modules/sacc/images/b_browse.png" title ="<{$lang_select}>"></a>&nbsp;<a
	href="sacc_acc_edit.php?org_id=<{$org_id}>&ac_id=<{$accounts[i].id}>"><img src="<{$xoops_url}>/modules/sacc/images/b_edit.png" title ="<{$lang_edit}>"></a></td>
-->
        </tr>
    <{/section}>
    <!-- end sets item loop -->

</table>

<!-- do not remove the following copyright section -->
<div align="right">
    <br>SACC (c) 2004, <a href="http://akitson.bbcb.co.uk" title="Click to see software author's web page" target="_blank">A Kitson.</a> UK.
</div>
<!-- end of copyright section -->
