DELIMITER //
CREATE OR REPLACE PROCEDURE AddTransactionDetails(IN v_IDCust INT)
BEGIN
    DECLARE v_IDProduct INT;
    DECLARE v_Quantity INT;
    DECLARE v_IsSelect CHAR(1);
    DECLARE v_TransactionID INT;
    DECLARE v_TotalPrice DECIMAL(10, 2) DEFAULT 0;
    DECLARE v_TotalVAT DECIMAL(10, 2) DEFAULT 0;
    DECLARE v_CurStockQty INT;
    DECLARE v_PricePerUnit DECIMAL(10, 2);
    DECLARE v_NOVAT DECIMAL(10, 2);
    DECLARE v_VAT DECIMAL(10, 2);
    DECLARE v_Status INT;
    DECLARE v_Seq INT DEFAULT 1;
    DECLARE done INT DEFAULT 0;

    DECLARE cur CURSOR FOR
        SELECT IDProduct, Quantity, IsSelect
        FROM Cart
        WHERE IDCust = v_IDCust;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    START TRANSACTION;

    OPEN cur;

    loop1: LOOP
        FETCH cur INTO v_IDProduct, v_Quantity, v_IsSelect;

        IF done = 1 THEN
            LEAVE loop1;
        END IF;

        IF v_IsSelect = 'T' THEN
            SET v_PricePerUnit = (SELECT PricePerUnit FROM Stock WHERE IDProduct = v_IDProduct);
            SET v_TotalPrice = v_TotalPrice + (v_PricePerUnit * v_Quantity);
        END IF;
    END LOOP loop1;

    CLOSE cur;

    SET v_Status = 1;

    INSERT INTO Transaction (TotalPrice, Timestamp, IDCust, IDStatus)
    VALUES (v_TotalPrice, NOW(), v_IDCust, v_Status);

    SELECT IDTransaction INTO v_TransactionID FROM Transaction ORDER BY IDTransaction DESC LIMIT 1;

    SET done = 0;

    OPEN cur;

    loop2: LOOP
        FETCH cur INTO v_IDProduct, v_Quantity, v_IsSelect;

        IF done = 1 THEN
            LEAVE loop2;
        END IF;

        IF v_IsSelect = 'T' THEN
            SET v_PricePerUnit = (SELECT PricePerUnit FROM Stock WHERE IDProduct = v_IDProduct);
            SET v_NOVAT = v_PricePerUnit * (100 / 107);
            SET v_VAT = v_PricePerUnit * (7 / 107);

            INSERT INTO TransactionDetail (IDTransaction, Seq, PRICE_NOVAT, QTY, IDProduct, VAT)
            VALUES (
                v_TransactionID,
                v_Seq,
                ROUND(v_NOVAT * v_Quantity, 2),
                v_Quantity,
                v_IDProduct,
                ROUND(v_VAT * v_Quantity, 2)
            );

            SELECT StockQtyFrontEnd INTO v_CurStockQty FROM Stock WHERE IDProduct = v_IDProduct;
            SET v_CurStockQty = v_CurStockQty - v_Quantity;
            UPDATE Stock SET StockQtyFrontEnd = v_CurStockQty WHERE IDProduct = v_IDProduct;

            SET v_TotalVAT = v_TotalVAT + (v_VAT * v_Quantity);

            SET v_Seq = v_Seq + 1;
        END IF;
    END LOOP loop2;

    UPDATE Transaction SET VAT = ROUND(v_TotalVAT, 2) WHERE IDTransaction = v_TransactionID;

    INSERT INTO transactionlog (IDTransaction, Seq, Timestamp, IDStatus, IDAdmin) VALUES (v_TransactionID, 1, NOW(), 1, NULL);

    DELETE FROM Cart WHERE IsSelect = 'T';

    CLOSE cur;

    COMMIT;
END
//
DELIMITER ;


DELIMITER //

CREATE OR REPLACE PROCEDURE UpdateTransactionStatus(
    IN p_IDTransaction INT, 
    IN p_NewIDStatus INT, 
    IN p_IDAdmin INT
)
BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE old_IDStatus INT;
    DECLARE new_seq INT;
    DECLARE v_IDProduct INT;
    DECLARE v_QTY INT;

    -- Declare a cursor for fetching product details
    DECLARE cur CURSOR FOR
        SELECT IDProduct, QTY 
        FROM TransactionDetail 
        WHERE IDTransaction = p_IDTransaction;

    -- Continue handler for cursor
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    -- Start a transaction
    START TRANSACTION;

    -- Get the current transaction status
    SELECT IDStatus INTO old_IDStatus
    FROM Transaction
    WHERE IDTransaction = p_IDTransaction;

    -- Validate the status update
    IF ((p_NewIDStatus = old_IDStatus + 1 OR p_NewIDStatus = old_IDStatus - 1) OR (p_NewIDStatus = 6)) AND p_NewIDStatus <= 6 THEN
        -- Update transaction status
        UPDATE Transaction
        SET IDStatus = p_NewIDStatus
        WHERE IDTransaction = p_IDTransaction;

        -- Insert log entry
        SELECT IFNULL(MAX(Seq), 0) + 1 INTO new_seq
        FROM TransactionLog
        WHERE IDTransaction = p_IDTransaction;

        INSERT INTO TransactionLog (
            IDTransaction, Seq, Timestamp, IDStatus, IDAdmin
        )
        VALUES (
            p_IDTransaction, new_seq, NOW(), p_NewIDStatus, p_IDAdmin
        );

        -- Process stock updates if status is 5 or 6
        IF p_NewIDStatus IN (5, 6) THEN
            OPEN cur;
            stock_update_loop: LOOP
                FETCH cur INTO v_IDProduct, v_QTY;

                -- Exit loop when no rows are left
                IF done THEN
                    LEAVE stock_update_loop;
                END IF;

                -- Update stock quantities
                IF p_NewIDStatus = 5 THEN
                    UPDATE stock
                    SET StockQtyBackEnd = StockQtyBackEnd - v_QTY
                    WHERE IDProduct = v_IDProduct;
                ELSEIF p_NewIDStatus = 6 THEN
                    UPDATE stock
                    SET StockQtyFrontEnd = StockQtyFrontEnd + v_QTY
                    WHERE IDProduct = v_IDProduct;
                END IF;
            END LOOP;
            CLOSE cur;
        END IF;
    END IF;

    -- Commit the transaction
    COMMIT;
END//

DELIMITER ;
