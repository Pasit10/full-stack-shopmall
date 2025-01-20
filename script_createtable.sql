CREATE DATABASE shopmall;

USE shopmall;


DROP TABLE TransactionDetail;
DROP TABLE TRANSACTION;
DROP TABLE Cart;

DROP TABLE CUSTOMER;
DROP TABLE stock;
DROP TABLE transactionlog;
DROP TABLE transactionstatus;
DROP TABLE admin;

CREATE TABLE CUSTOMER(
    IDCust INT PRIMARY KEY AUTO_INCREMENT,
    Custname VARCHAR(50),
    Password VARCHAR(256),
    Sex CHAR(1),
    Address VARCHAR(100),
    TEL VARCHAR(20)
);

CREATE TABLE ADMIN(
    IDAdmin INT PRIMARY KEY AUTO_INCREMENT,
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
    IDAdmin INT,
    PRIMARY KEY (IDTransaction,Seq),
    FOREIGN KEY (IDStatus) REFERENCES TransactionStatus(IDStatus),
    FOREIGN KEY (IDAdmin) REFERENCES ADMIN(IDADMIN)
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
