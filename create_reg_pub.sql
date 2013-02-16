CREATE  `portal`.`vregistropub` AS select `a`.`oggetto` AS `oggetto`,`b`.`nome` AS `ente`,`t`.`descrizione` AS `tipo` from ((`portal`.`registro` `a` join `portal`.`enti` `b`) join `portal`.`tipi_atti` `t`) where ((`a`.`id_ente` = `b`.`id`) and (`a`.`id_tipo` = `t`.`id`))

