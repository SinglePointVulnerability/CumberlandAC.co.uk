<?php
$sqlMTChall = "
SELECT DISTINCT races.ChampYear
	,tblGenPoints.RunnerDiv
	,tblGenPoints.RunnerSex
	,tblGenPoints.`Champ Total` AS ChampTotal
	,tblGenPoints.RunnerID
	,tblGenPoints.RunnerName
FROM tblRaceTimes
INNER JOIN (
	SELECT DISTINCT rt.RunnerID
		,CONCAT (
			run.RunnerFirstName
			,' '
			,run.RunnerSurname
			) AS RunnerName
		,run.RunnerDiv
		,run.RunnerSex
		,COALESCE(topXPoints_gender_split(rt.RunnerID, 16, 2024, 6), 0) AS 'Champ Total'
	FROM tblRaceTimes rt
	INNER JOIN tblRaces rac ON rac.RaceID = rt.RaceID
	INNER JOIN tblRunners run ON run.RunnerID = rt.RunnerID
	WHERE rac.ChampYear = 2024
	ORDER BY run.RunnerSex ASC
		,COALESCE(topXPoints_gender_split(rt.RunnerID, 16, 2024, 6), 0) DESC
	) tblGenPoints ON tblRaceTimes.RunnerID = tblGenPoints.RunnerID
INNER JOIN (
	SELECT tblRaces.RaceID
		,tblRaces.RaceDate
		,tblRaces.RaceCode
		,tblRaces.ChampYear
	FROM tblRaces
	) races ON tblRaceTimes.RaceID = races.RaceID
WHERE races.ChampYear = 2024
	AND `Champ Total` > 0
ORDER BY races.ChampYear
	,tblGenPoints.RunnerSex ASC
	,tblGenPoints.`Champ Total` DESC;";

return $sqlMTChall;
?>