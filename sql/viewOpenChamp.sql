SELECT DISTINCT races.ChampYear
	,tblDivPoints.RunnerDiv
	,tblDivPoints.RunnerSex
	,tblDivPoints.`Champ Total` AS ChampTotal
	,tblDivPoints.RunnerID
	,tblDivPoints.RunnerName
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
		,topXPoints_division_gender_split(rt.RunnerID, 32, 2023, 3) AS 'Top 3 Short-Med'
		,topXPoints_division_gender_split(rt.RunnerID, 4, 2023, 3) AS 'Top 3 Long'
		,COALESCE(topXPoints_division_gender_split(rt.RunnerID, 32, 2023, 3), 0) + COALESCE(topXPoints_division_gender_split(rt.RunnerID, 4, 2023, 3), 0) AS 'Champ Total'
	FROM tblRaceTimes rt
	INNER JOIN tblRaces rac ON rac.RaceID = rt.RaceID
	INNER JOIN tblRunners run ON run.RunnerID = rt.RunnerID
	WHERE rac.ChampYear = 2023
	ORDER BY run.RunnerDiv DESC
		,run.RunnerSex ASC
		,COALESCE(topXPoints_division_gender_split(rt.RunnerID, 32, 2023, 3), 0) + COALESCE(topXPoints_division_gender_split(rt.RunnerID, 4, 2023, 3), 0) DESC
	) tblDivPoints ON tblRaceTimes.RunnerID = tblDivPoints.RunnerID
INNER JOIN (
	SELECT tblRaces.RaceID
		,tblRaces.RaceDate
		,tblRaces.RaceCode
		,tblRaces.ChampYear
	FROM tblRaces
	) races ON tblRaceTimes.RaceID = races.RaceID
WHERE races.ChampYear = 2023
	AND `Champ Total` > 0
ORDER BY races.ChampYear
	,tblDivPoints.RunnerDiv DESC
	,tblDivPoints.RunnerSex
	,tblDivPoints.`Champ Total` DESC