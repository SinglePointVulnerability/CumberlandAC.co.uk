<?php
$sqlAgeGradedChamp = "
SELECT DISTINCT races.ChampYear
	,tblAgeGraded.RunnerSex
	,tblAgeGraded.`Champ Total` AS ChampTotal
	,tblAgeGraded.RunnerID
	,tblAgeGraded.RunnerName
FROM tblRaceTimes
INNER JOIN (
	SELECT DISTINCT rt.RunnerID
		,CONCAT (
			run.RunnerFirstName
			,' '
			,run.RunnerSurname
			) AS RunnerName
		,run.RunnerSex
		,topXPoints_masters_gender_split(rt.RunnerID, run.RunnerSex, 32, 2024, 3) AS `Top 3 Short-Med`
		,topXPoints_masters_gender_split(rt.RunnerID, run.RunnerSex, 4, 2024, 3) AS `Top 3 Long`
		,COALESCE(topXPoints_masters_gender_split(rt.RunnerID, run.RunnerSex, 32, 2024, 3), 0) + COALESCE(topXPoints_masters_gender_split(rt.RunnerID, run.RunnerSex, 4, 2024, 3), 0) AS `Champ Total`
	FROM tblRaceTimes rt
	INNER JOIN tblRaces rac ON rac.RaceID = rt.RaceID
	INNER JOIN tblRunners run ON run.RunnerID = rt.RunnerID
	WHERE rac.ChampYear = 2024
	ORDER BY run.RunnerSex ASC
		,COALESCE(topXPoints_masters_gender_split(rt.RunnerID, run.RunnerSex, 32, 2024, 3), 0) + COALESCE(topXPoints_masters_gender_split(rt.RunnerID, run.RunnerSex, 4, 2024, 3), 0) DESC
	) tblAgeGraded ON tblRaceTimes.RunnerID = tblAgeGraded.RunnerID
INNER JOIN (
	SELECT tblRaces.RaceID
		,tblRaces.RaceDate
		,tblRaces.RaceCode
		,tblRaces.ChampYear
	FROM tblRaces
	) races ON tblRaceTimes.RaceID = races.RaceID
WHERE races.ChampYear = 2024
	AND `Champ Total` > 0
	AND tblRaceTimes.RunnerAgeOn1stJan >= 35
ORDER BY races.ChampYear
	,tblAgeGraded.RunnerSex
	,tblAgeGraded.`Champ Total` DESC";

return $sqlAgeGradedChamp;
?>