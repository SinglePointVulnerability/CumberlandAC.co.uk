<?php
$role = isset($_SESSION['role']) ? $_SESSION['role'] : "";
$name = isset($_SESSION['name']) ? $_SESSION['name'] : "";

if($role <> "") {
	if(str_contains($role, 'club member')) {
?>
    <div class="floating-content">
        <form id="member-actions" method="post" action="memberLanding.php">
            <table>
                <tr>
                    <td colspan="2" class="txt"><b><?php echo $name; ?></b></td>
                </tr>
                <tr>
                    <td colspan="2" class="txt">Role: <b><?php echo $role; ?></b></td>
                </tr>
                <tr>
                    <td colspan="2" class="txt"><br><b>Actions</b></td>
                </tr>
                <tr>
                    <td></td>
                    <td><button type="submit" formaction="logout.php">Logout</button></td>
                </tr>
                <tr>
                    <td><button type="submit" formaction="welfareOfficers.php">Club Welfare Officers</button></td>
                    <td></td>
                </tr>
            </table>
        </form>
    </div>
<?php
} else {
?>
    <div class="floating-content">
        <form id="admin-actions" method="post" action="adminLanding.php">
            <table>
                <tr>
                    <td colspan="2" class="txt"><b><?php echo $name; ?></b></td>
                </tr>
                <tr>
                    <td colspan="2" class="txt">Role: <b><?php echo $role; ?></b></td>
                </tr>
                <tr>
                    <td colspan="2" class="txt"><br><b>Actions</b></td>
                </tr>
                <tr>
                    <td></td>
                    <td><button type="submit" formaction="logout.php">Logout</button></td>
                </tr>
<?php
    // Handling different roles
    if ($role == "site admin" || $role == "club stats") {
?>
                <tr>
                    <td><button type="submit" formaction="addRaceTime.php">Add race time</button></td>
                    <td></td>
                </tr>
                <tr>
                    <td><button type="submit" formaction="updateMemberDetails.php">Member details</button></td>
                    <td></td>
                </tr>
<?php
    }

    if ($role == "site admin" || $role == "run leader") {
?>
                <tr>
                    <td><button type="submit" formaction="updateTrainingRunLeader_Navigation.php">Training & Run Leaders</button></td>
                    <td></td>
                </tr>
<?php
    }

    if ($role == "race committee" || $role == "membership sec") {
?>
                <tr>
                    <td><button type="submit" formaction="updateMemberDetails.php">Member details</button></td>
                    <td></td>
                </tr>
<?php
    }
    if ($role == "site admin" || $role == "jog journalist") {
?>
                <tr>
                    <td><button type="submit" formaction="newsletter.php">Newsletter Publisher</button></td>
                    <td></td>
                </tr>
<?php
    }
?>
            </table>
        </form>
    </div>
<?php
	}
}
?>
