USE shopmall;


DROP TABLE TransactionDetail;
DROP TABLE TRANSACTION;
DROP TABLE Cart;

DROP TABLE CUSTOMER;
DROP TABLE stock;
DROP TABLE transactionlog;
DROP TABLE transactionstatus;

CREATE TABLE CUSTOMER(
    IDCust INT PRIMARY KEY AUTO_INCREMENT,
    Custname VARCHAR(50),
    Password VARCHAR(256),
    Sex CHAR(1),
    Address VARCHAR(100),
    TEL VARCHAR(20)
);

CREATE TABLE ADMIN(
    IDCust INT PRIMARY KEY AUTO_INCREMENT,
    AdminName VARCHAR(50),
    Password VARCHAR(256),
    TEL VARCHAR(20)
);

CREATE TABLE STOCK (
    IDProduct INT PRIMARY KEY AUTO_INCREMENT,
    ProductName VARCHAR(50),
    PricePerUnit DECIMAL(8,2),
    Detail VARCHAR(255),
    StockQtyFrontEnd INT,
    StockQtyBackEnd INT,
    ProductImagePath VARCHAR(255)
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

CREATE TABLE TransactionStatus (
    IDStatus INT,
    Name VARCHAR(20),
    PRIMARY KEY (IDStatus)
)

CREATE TABLE TransactionLog (
    IDTransaction INT NOT NULL,
    Seq INT,
    Timestamp Timestamp,
    IDStatus INT NOT NULL,
    PRIMARY KEY (IDTransaction,Seq),
    FOREIGN KEY (IDStatus) REFERENCES TransactionStatus(IDStatus)
)


CREATE TABLE Transaction(
    IDTransaction INT PRIMARY KEY AUTO_INCREMENT,
    TotalPrice DECIMAL(8,2),
    Timestamp TIMESTAMP,
    VAT DECIMAL(10,2),
    IDCust INT NOT NULL,
    IDStatus INT NOT NULL,
    FOREIGN KEY (IDCust) REFERENCES Customer(IDCust),
    FOREIGN KEY (IDStatus) REFERENCES TransactionStatus(IDStatus)
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
    DECLARE v_Status INT;
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

    DELETE FROM Cart WHERE IsSelect = 'T';

    CLOSE cur;

    COMMIT;
END
//
DELIMITER ;