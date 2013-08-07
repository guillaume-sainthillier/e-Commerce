DROP TRIGGER IF EXISTS demande_before_insert;
DROP TRIGGER IF EXISTS t_a_i_commande;
delimiter //
CREATE TRIGGER t_a_i_commande AFTER INSERT ON commande
FOR EACH ROW
BEGIN

DECLARE done INT DEFAULT 0;
DECLARE v_idProd,v_idProd2,v_idProd3 CHAR(5);
DECLARE v_nbF INT;
DECLARE c1 CURSOR FOR
select dc.idProd,dc2.idProd,count(*) 
	from detail_commande dc,detail_commande dc2
		WHERE
			dc.idCom = dc2.idCom 
			AND dc2.idProd <> dc.idProd 
			AND dc2.idCom =	
                (SELECT idCom from commande WHERE idCom = NEW.idCom)  
	GROUP BY 1,2;

OPEN c1;
    REPEAT
        FETCH c1 INTO v_idProd,v_idProd2,v_nbF;			
			IF (select idProd1 from proposer WHERE idProd1 = v_idProd AND idProd2 = v_idProd2) IS NULL THEN
				INSERT INTO rapport(libelle) VALUES("j'insere PROPOSER('"|| v_idProd || "','"|| v_idProd2 || "','"|| v_nbF || "')");
				INSERT INTO PROPOSER(idProd1,idProd2,nbFois) VALUES('v_idProd','v_idProd2','v_nbF');
			ELSE
				INSERT INTO rapport(libelle) VALUES("jupdate proposer où idProd1  =  '"|| v_idProd || "' ET idProd2 = '"|| v_idProd2 || "'");
				UPDATE PROPOSER
					SET nbFois = nbFois + 1
					WHERE idProd1  = v_idProd AND idProd2 = v_idProd2;
			END IF;
				
    UNTIL done END REPEAT;
CLOSE c1;
END;
//
delimiter ;
