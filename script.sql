USE shopmall;


DROP TABLE TransactionDetail;
DROP TABLE TRANSACTION;
DROP TABLE Cart;

DROP TABLE CUSTOMER;
DROP TABLE stock;

CREATE TABLE CUSTOMER(
    IDCust INT PRIMARY KEY AUTO_INCREMENT,
    Custname VARCHAR(50),
    Password VARCHAR(256),
    Sex CHAR(1),
    Address VARCHAR(100),
    TEL VARCHAR(20)
);

CREATE TABLE STOCK (
    IDProduct INT PRIMARY KEY AUTO_INCREMENT,
    ProductName VARCHAR(50),
    PricePerUnit DECIMAL(8,2),
    Detail VARCHAR(255),
    StockQty INT,
    ProductImagePath VARCHAR(255)
);

CREATE TABLE TRANSACTION(
    IDTransaction INT PRIMARY KEY AUTO_INCREMENT,
    TotalPrice DECIMAL(8,2),
    Timestamp TIMESTAMP,
    VAT DECIMAL(8,2),
    IDCust INT NOT NULL,
    FOREIGN KEY (IDCust) REFERENCES Customer(IDCust)
);

CREATE TABLE TransactionDetail (
    IDtransaction INT NOT NULL,
    Seq INT NOT NULL,
    PRICE_NOVAT DECIMAL(8,2),
    VAT DECIMAL(8,2),
    QTY INT,
    IDProduct INT NOT NULL,
    PRIMARY KEY (IDtransaction, Seq),
    FOREIGN KEY (IDtransaction) REFERENCES Transaction(IDtransaction),
    FOREIGN KEY (IDProduct) REFERENCES Stock(IDProduct)
);

CREATE TABLE Cart(
    IDCust INT NOT NULL,
    IDProduct INT NOT NULL,
    Quantity INT,
    IsSelect CHAR(1),
    PRIMARY KEY (IDCust, IDProduct),
    FOREIGN KEY (IDCust) REFERENCES Customer(IDCust),
    FOREIGN KEY (IDProduct) REFERENCES Stock(IDProduct)
);

DROP PROCEDURE IF EXISTS AddTransactionDetails;


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
    DECLARE v_Seq INT DEFAULT 1;
    DECLARE done INT DEFAULT 0;

    DECLARE cur CURSOR FOR
        SELECT IDProduct, Quantity, IsSelect
        FROM Cart
        WHERE IDCust = v_IDCust;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    -- Start transaction
    START TRANSACTION;

    OPEN cur;

    loop1: LOOP
        FETCH cur INTO v_IDProduct, v_Quantity, v_IsSelect;

        IF done = 1 THEN
            LEAVE loop1;
        END IF;

        IF v_IsSelect = 'T' THEN
            SET v_PricePerUnit = (SELECT PricePerUnit FROM Stock WHERE IDProduct = v_IDProduct);
            SET v_NOVAT = v_PricePerUnit * (100 / 107);
            SET v_VAT = v_PricePerUnit * (7 / 107);
            SET v_TotalPrice = v_TotalPrice + (v_PricePerUnit * v_Quantity);
            SET v_TotalVAT = v_TotalVAT + (v_VAT * v_Quantity);
        END IF;
    END LOOP loop1;

    CLOSE cur;

    INSERT INTO TRANSACTION (TotalPrice, Timestamp, VAT, IDCust)
    VALUES (v_TotalPrice, NOW(), v_TotalVAT, v_IDCust);

    SELECT IDTransaction INTO v_TransactionID FROM TRANSACTION ORDER BY IDTransaction DESC LIMIT 1;

    -- Reset handler for the second loop
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
                v_NOVAT * v_Quantity,
                v_Quantity,
                v_IDProduct,
                v_VAT * v_Quantity
            );

            SELECT StockQty INTO v_CurStockQty FROM Stock WHERE IDProduct = v_IDProduct;
            SET v_CurStockQty = v_CurStockQty - v_Quantity;
            UPDATE Stock SET StockQty = v_CurStockQty WHERE IDProduct = v_IDProduct;

            SET v_Seq = v_Seq + 1;
        END IF;
    END LOOP loop2;

    DELETE FROM Cart WHERE IsSelect = 'T';

    CLOSE cur;

    -- Commit transaction
    COMMIT;
END
//
DELIMITER ;
