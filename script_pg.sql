
CREATE DATABASE "QC" ON  PRIMARY
( NAME = N'QC_Data', FILENAME = N'c:\Program Files\Microsoft SQL Server\MSSQL10_50.SQLEXPRESS\MSSQL\DATA\QC.MDF' , SIZE = 67904KB , MAXSIZE = UNLIMITED, FILEGROWTH = 10%)
LOG ON
( NAME = N'QC_Log', FILENAME = N'c:\Program Files\Microsoft SQL Server\MSSQL10_50.SQLEXPRESS\MSSQL\DATA\QC_1.LDF' , SIZE = 3840KB , MAXSIZE = UNLIMITED, FILEGROWTH = 10%)
ALTER DATABASE "QC" SET COMPATIBILITY_LEVEL = 80
ALTER DATABASE "QC"
ALTER DATABASE "QC"
ALTER DATABASE "QC"
ALTER DATABASE "QC"
ALTER DATABASE "QC"
ALTER DATABASE "QC"
ALTER DATABASE "QC"
ALTER DATABASE "QC"
ALTER DATABASE "QC"
ALTER DATABASE "QC"
ALTER DATABASE "QC" SET CURSOR_DEFAULT  GLOBAL
ALTER DATABASE "QC"
ALTER DATABASE "QC"
ALTER DATABASE "QC"
ALTER DATABASE "QC"
ALTER DATABASE "QC" SET  ENABLE_BROKER
ALTER DATABASE "QC"
ALTER DATABASE "QC"
ALTER DATABASE "QC"
ALTER DATABASE "QC"
ALTER DATABASE "QC" SET PARAMETERIZATION SIMPLE
ALTER DATABASE "QC"
ALTER DATABASE "QC"
ALTER DATABASE "QC" SET  READ_WRITE
ALTER DATABASE "QC" SET RECOVERY SIMPLE
ALTER DATABASE "QC" SET  MULTI_USER
ALTER DATABASE "QC" SET PAGE_VERIFY TORN_PAGE_DETECTION
ALTER DATABASE "QC"
CREATE USER "antony" WITHOUT LOGIN WITH DEFAULT_SCHEMA="antony"
CREATE SCHEMA "antony" AUTHORIZATION "antony"
CREATE TABLE "USERLIST"(
"USERID" "varchar"(50) NULL,
"PASSWORD" "varchar"(10) NULL,
"FIRSTNAME" "varchar"(50) NULL,
"LASTNAME" "varchar"(50) NULL,
"GROUPID" "varchar"(5) NULL,
"STATUSLOGIN" "varchar"(1) NULL,
"LASTMODUSER" "varchar"(10) NULL,
"LASTMODDATE" "timestamp" NULL
)
CREATE TABLE "urutscala"(
"period" "varchar"(6) NULL,
"nsa" "numeric"(18, 0) NULL,
"sa" "numeric"(18, 0) NULL
)
CREATE TABLE "TREEMENU"(
"STATUS" "char"(1) NULL,
"PARENT" "char"(4) NULL,
"KODEMENU" "char"(4) NOT NULL,
"NAMAMENU" "varchar"(50) NULL,
"ID" "numeric"(18, 0) NULL,
"NOU" "numeric"(18, 0) NULL
)
CREATE TABLE "TOTALBATCH"(
"PARTNO" "varchar"(17) NULL,
"PARTNAME" "varchar"(25) NULL,
"SPEC" "varchar"(50) NULL,
"PRQTY" "double precision" NULL,
"POQTY" "varchar"(50) NULL,
"UNIT" "varchar"(3) NULL,
"BATCHDATE" "timestamp" NULL,
"BATCHKE" "numeric"(18, 0) NULL,
"SUPPCODE" "varchar"(7) NULL
)
CREATE TABLE "TJAM"(
"JAM" "timestamp" NULL
)
CREATE TABLE "tempo"(
"kode" "varchar"(12) NULL,
"nama" "varchar"(50) NULL
)
CREATE TABLE "tblUserGroup"(
"GroupID" "varchar"(14) NULL,
"GroupName" "varchar"(50) NULL,
"LastModUser" "varchar"(10) NULL,
"LastModDate" "timestamp" NULL
)
CREATE TABLE "tblUser"(
"USERID" "varchar"(13) NOT NULL,
"PASSWORD" "varchar"(10) NULL,
"FIRSTNAME" "varchar"(50) NULL,
"LASTNAME" "varchar"(50) NULL,
"GROUPID" "varchar"(14) NULL,
"STATUSLOGIN" "varchar"(1) NULL,
"MENUSECURITY" "varchar"(400) NULL,
"LastModUser" "varchar"(10) NULL,
"LastModDate" "timestamp" NULL
)
CREATE TABLE "tblTree"(
"STATUS" "char"(10) NULL,
"PARENT" "char"(10) NULL,
"KODEMENU" "char"(10) NOT NULL,
"NAMAMENU" "varchar"(50) NULL
)
CREATE TABLE "tblParameter"(
"DONumberFlag" "varchar"(1) NULL,
"InvoiceMask" "varchar"(3) NULL,
"TransactionNo" "varchar"(6) NULL,
"PaymentTransMask" "varchar"(1) NULL,
"TransactionNoPayment" "varchar"(5) NULL,
"PriceInclTax" "varchar"(1) NULL,
"VAT" "varchar"(2) NULL,
"ReturnInvoiceMask" "varchar"(3) NULL,
"DOMask" "varchar"(3) NULL,
"SwapMask" "varchar"(3) NULL,
"ExpenseMask" "varchar"(3) NULL,
"A" "double precision" NULL,
"B" "double precision" NULL,
"C" "double precision" NULL,
"D" "double precision" NULL,
"E" "double precision" NULL,
"F" "double precision" NULL,
"G" "double precision" NULL,
"H" "double precision" NULL,
"I" "double precision" NULL,
"J" "double precision" NULL,r
"K" "double precision" NULL,
"L" "double precision" NULL,
"M" "double precision" NULL,
"N" "double precision" NULL,
"O" "double precision" NULL,
"P" "double precision" NULL,
"Q" "double precision" NULL,
"R" "double precision" NULL,
"S" "double precision" NULL,
"T" "double precision" NULL,
"U" "double precision" NULL,
"V" "double precision" NULL,
"W" "double precision" NULL,
"X" "double precision" NULL,
"Y" "double precision" NULL,
"Z" "double precision" NULL,
"AA" "double precision" NULL,
"BB" "double precision" NULL,
"CC" "double precision" NULL,
"DD" "double precision" NULL,
"EE" "double precision" NULL,
"CompanyCodes" "varchar"(2) NULL,
"CompanyName" "varchar"(50) NULL,
"ActiveYear" "varchar"(2) NULL,
"ValidCurr" "double precision" NULL,
"LastDONumber" "integer" NULL,
"TTNo" "integer" NULL,
"digitleft" "integer" NULL,
"LastModUser" "varchar"(10) NULL,
"LastModDate" "timestamp" NULL
)
CREATE TABLE "SUPPLIER"(
"SUPPCODE" "varchar"(7) NOT NULL,
"SUPPNAME" "varchar"(50) NULL,
"ADDRESS" "varchar"(150) NULL,
"TELP" "varchar"(30) NULL,
"FAX" "varchar"(30) NULL,
"TAX" "char"(10) NULL,
"ATTN" "varchar"(50) NULL,
"CREDIT" "double precision" NULL,
"BLACKLIST" "boolean" NULL,
"PURCH" "varchar"(1) NULL,
"MINORDER" "numeric"(18, 0) NULL,
"LEAD" "numeric"(18, 0) NULL
)
CREATE TABLE "STOCK"(
"PARTNO" "varchar"(17) NOT NULL,
"PARTNAME" "varchar"(35) NULL,
"SPEC" "varchar"(30) NULL,
"UNIT" "varchar"(3) NULL,
"CATEGORY" "varchar"(1) NULL,
"WHQ" "double precision" NULL,
"WHIO" "double precision" NULL,
"WHR" "double precision" NULL,
"WHPRICE" "double precision" NULL,
"IRQ" "double precision" NULL,
"IRIP" "double precision" NULL,
"IRR" "double precision" NULL,
"IRPRICE" "double precision" NULL,
"BLQ" "double precision" NULL,
"BLIP" "double precision" NULL,
"BLR" "double precision" NULL,
"BLPRICE" "double precision" NULL,
"TWQ" "double precision" NULL,
"TWIP" "double precision" NULL,
"TWR" "double precision" NULL,
"TWPRICE" "double precision" NULL,
"MIQ" "double precision" NULL,
"MIIP" "double precision" NULL,
"MIR" "double precision" NULL,
"MIPRICE" "double precision" NULL,
"SAQ" "double precision" NULL,
"SAIP" "double precision" NULL,
"SAR" "double precision" NULL,
"SAPRICE" "double precision" NULL,
"COMMON" "varchar"(1) NULL,
"TOTQTY" "double precision" NULL,
"TOTPRICE" "double precision" NULL,
"FIXED" "varchar"(1) NULL,
"LOCATION" "varchar"(5) NULL,
"MINSTOCK" "double precision" NULL,
"REJECT" "double precision" NULL,
"MAXSTOCK" "double precision" NULL,
CONSTRAINT "PK_STOCK" PRIMARY KEY CLUSTERED
(
"PARTNO" ASC
)
)
CREATE TABLE "status"(
"sta" "varchar"(50) NULL
)
CREATE TABLE "spbdarilogistik"(
"partno" "varchar"(17) NULL,
"partname" "varchar"(50) NULL,
"nospb" "varchar"(25) NULL,
"spbdate" "timestamp" NULL,
"jumlah" "double precision" NULL,
"keterangan" "varchar"(100) NULL
)
CREATE TABLE "serahkelogistik"(
"nospb" "varchar"(25) NULL,
"partno" "varchar"(17) NULL,
"partname" "varchar"(50) NULL,
"nosp" "varchar"(25) NULL,
"spbdate" "timestamp" NULL,
"jumlah" "double precision" NULL,
"keterangan" "varchar"(100) NULL
)
CREATE TABLE "sementara"(
"suppcode" "varchar"(7) NULL
)
CREATE TABLE "semen"(
"partno" "varchar"(17) NULL,
"partname" "varchar"(50) NULL
)
CREATE TABLE "REJECTHDR"(
"REJECTNO" "varchar"(12) NOT NULL,
"DATEREJECT" "timestamp" NULL,
"WEEKDAY" "varchar"(6) NULL,
"DIVISION" "varchar"(5) NULL,
"PROCESSBY" "varchar"(50) NULL,
"PRODID" "varchar"(15) NULL,
"PRODQTY" "numeric"(18, 0) NULL
)
CREATE TABLE "REJECTDTL"(
"REJECTNO" "varchar"(12) NULL,
"PARTNO" "varchar"(16) NULL,
"PARTNAME" "varchar"(50) NULL,
"SPEC" "varchar"(50) NULL,
"UNIT" "varchar"(3) NULL,
"QTY" "double precision" NULL,
"REMARKS" "varchar"(100) NULL,
"CTG" "varchar"(2) NULL,
"qtyop" "double precision" NULL,
"qtym" "double precision" NULL,
"qtyqc" "double precision" NULL
)
CREATE TABLE "PO"(
"PONO" "varchar"(15) NOT NULL,
"PODATE" "timestamp" NULL,
"PRNO" "varchar"(15) NULL,
"SUPPCODE" "varchar"(7) NULL,
"ATTN" "varchar"(20) NULL,
"WEEKNEED" "varchar"(6) NULL,
"TOPS" "double precision" NULL,
"SHIPMENT" "varchar"(40) NULL,
"NOTES" "varchar"(100) NULL,
"TAX" "integer" NULL,
"DISCOUNT1" "double precision" NULL,
"POTYPE" "char"(1) NULL,
"STATUS" "varchar"(1) NULL,
"PURCH" "varchar"(1) NULL,
"PRSSTS" "varchar"(1) NULL,
"DELDATE" "timestamp" NULL
)
CREATE TABLE "pemeriksa_MST"(
"nama" "varchar"(50) NULL
)
CREATE TABLE "pemeriksa"(
"nama" "varchar"(50) NULL
)
CREATE TABLE "PARAMETER"(
"PRMASKINGSHM" "varchar"(4) NULL,
"PRMASKING" "varchar"(4) NULL,
"PRMASKINGIMP" "varchar"(4) NULL,
"POMASKINGSHM" "varchar"(4) NULL,
"POMASKING" "varchar"(4) NULL,
"RGOODSMASKING" "varchar"(4) NULL,
"RGOODSMASKINGSHM" "varchar"(4) NULL,
"RGOODSMASKINGIMP" "varchar"(4) NULL,
"COMPANYCODES" "char"(2) NULL,
"COMPANYNAMES" "varchar"(50) NULL,
"ACTIVEPERIOD" "varchar"(6) NULL,
"RGISOMASKING" "varchar"(4) NULL,
"ACTIVEDATE" "timestamp" NULL,
"SERVERNAME" "varchar"(50) NOT NULL
)
CREATE TABLE "MTVREPORT"(
"PERIOD" "varchar"(6) NULL,
"PARTNO" "varchar"(16) NULL,
"RG" "double precision" NULL,
"MTV" "double precision" NULL,
"TSM" "double precision" NULL,
"ADJ" "double precision" NULL,
"RTL" "double precision" NULL,
"RGR" "double precision" NULL,
"PR" "double precision" NULL,
"RJT" "double precision" NULL
)
CREATE TABLE "MATERIALCATEGORY"(
"CATEGORYID" "integer" NOT NULL,
"DESCRIPTION" "varchar"(50) NULL
)
CREATE TABLE "lokalimport"(
"totimport" "numeric"(18, 0) NULL,
"totlokalY" "numeric"(18, 0) NULL,
"totlokalN" "numeric"(18, 0) NULL,
"tgl1" "timestamp" NULL,
"tgl2" "timestamp" NULL
)
CREATE TABLE "libur"(
"period" "varchar"(6) NULL,
"n" "numeric"(18, 0) NULL
)
CREATE TABLE "lagi"(
"JAM" "timestamp" NULL
)
CREATE TABLE "IIRIMPORT"(
"TGLMASUK" "timestamp" NULL,
"PARTNO" "varchar"(17) NULL,
"PARTNAME" "varchar"(50) NULL,
"JUMLAH" "numeric"(18, 0) NULL,
"SAMPLING" "numeric"(18, 0) NULL,
"TGLMULAI" "timestamp" NULL,
"TGLSELESAI" "timestamp" NULL,
"LAMABATCH" "numeric"(18, 0) NULL,
"LAMAPART" "numeric"(18, 0) NULL,
"KETERANGAN" "char"(10) NULL,
"NODOC" "varchar"(50) NULL,
"jumlibur" "numeric"(18, 0) NULL
)
CREATE TABLE "IIRb"(
"batchdate" "timestamp" NULL,
"suppcode" "varchar"(7) NULL,
"partno" "varchar"(17) NULL,
"partname" "varchar"(50) NULL,
"nodoc" "varchar"(25) NULL,
"batchsize" "double precision" NULL,
"simplesize" "double precision" NULL,
"levela" "numeric"(18, 0) NULL,
"aql" "double precision" NULL,
"status" "varchar"(25) NULL,
"pemeriksa" "varchar"(50) NULL,
"start" "timestamp" NULL,
"finish" "timestamp" NULL,
"duration" "varchar"(50) NULL,
"iirdate" "timestamp" NULL,
"batchke" "numeric"(18, 0) NULL,
"untuk" "varchar"(12) NULL,
"tglmulai" "timestamp" NULL,
"tglselesai" "timestamp" NULL,
"lamapart" "numeric"(18, 0) NULL,
"jumlibur" "numeric"(18, 0) NULL
)
CREATE TABLE "IIR"(
"batchdate" "timestamp" NULL,
"suppcode" "varchar"(7) NULL,
"partno" "varchar"(17) NULL,
"partname" "varchar"(50) NULL,
"nodoc" "varchar"(25) NULL,
"batchsize" "double precision" NULL,
"simplesize" "double precision" NULL,
"levela" "numeric"(18, 0) NULL,
"aql" "double precision" NULL,
"status" "varchar"(25) NULL,
"pemeriksa" "varchar"(50) NULL,
"start" "timestamp" NULL,
"finish" "timestamp" NULL,
"duration" "varchar"(50) NULL,
"iirdate" "timestamp" NULL,
"batchke" "numeric"(18, 0) NULL,
"untuk" "varchar"(12) NULL,
"tglmulai" "timestamp" NULL,
"tglselesai" "timestamp" NULL,
"lamapart" "numeric"(18, 0) NULL,
"jumlibur" "numeric"(18, 0) NULL
)
CREATE TABLE "DIVISION"(
"DIVCD" "varchar"(2) NULL,
"DIVNM" "varchar"(50) NULL,
"CHIEFNM" "varchar"(30) NULL
)
CREATE TABLE "CUSTOMER"(
"CUSTCODE" "varchar"(15) NOT NULL,
"CUSTNAME" "varchar"(50) NULL,
"ADDRESS1" "varchar"(50) NULL,
"ADDRESS2" "varchar"(50) NULL,
"NPWP" "varchar"(15) NULL,
"PKP" "double precision" NULL,
"TELP1" "varchar"(30) NULL,
"FAX1" "varchar"(30) NULL
)
CREATE TABLE "CUSTCOUNTER"(
"A" "integer" NULL,
"B" "integer" NULL,
"C" "integer" NULL,
"D" "integer" NULL,
"E" "integer" NULL,
"F" "integer" NULL,
"G" "integer" NULL,
"H" "integer" NULL,
"I" "integer" NULL,
"J" "integer" NULL,
"K" "integer" NULL,
"L" "integer" NULL,
"M" "integer" NULL,
"N" "integer" NULL,
"O" "integer" NULL,
"P" "integer" NULL,
"Q" "integer" NULL,
"R" "integer" NULL,
"S" "integer" NULL,
"T" "integer" NULL,
"U" "integer" NULL,
"V" "integer" NULL,
"W" "integer" NULL,
"X" "integer" NULL,
"Y" "integer" NULL,
"Z" "integer" NULL
)
CREATE TABLE "COUNTER"(
"PERIOD" "varchar"(6) NULL,
"PONO" "double precision" NULL,
"POSHM" "double precision" NULL,
"PRIMP" "double precision" NULL,
"PRCNO1" "double precision" NULL,
"PRCNO2" "double precision" NULL,
"PRSHM" "double precision" NULL,
"RGNO" "double precision" NULL,
"RGNOSHM" "double precision" NULL,
"RGRNO" "double precision" NULL,
"RTRNO" "double precision" NULL,
"DONO" "double precision" NULL,
"PRONO" "double precision" NULL,
"RJTNO" "double precision" NULL,
"RQSIR" "double precision" NULL,
"RQSBL" "double precision" NULL,
"RQSTW" "double precision" NULL,
"RQSWH" "double precision" NULL,
"RQSSA" "double precision" NULL,
"RJTIR" "double precision" NULL,
"RJTBL" "double precision" NULL,
"RJTTW" "double precision" NULL,
"RJTWH" "double precision" NULL,
"RJTSA" "double precision" NULL,
"MAINTID" "double precision" NULL,
"DOWNID" "double precision" NULL,
"PRODID" "double precision" NULL,
"RETID" "double precision" NULL,
"RGISO" "double precision" NULL,
"POIMP" "double precision" NULL,
"RGIMP" "double precision" NULL,
"ROT" "double precision" NULL,
"RIN" "double precision" NULL,
"RQSMI" "double precision" NULL,
"RJTMI" "double precision" NULL,
"RQSAC" "double precision" NULL,
"RJTAC" "double precision" NULL,
"RJTNNO" "double precision" NULL
)
CREATE TABLE "COMPANYPARAMETER"(
"BRANCHCODE" "varchar"(10) NULL,
"BRANCHNAME" "varchar"(50) NULL,
"ADDRESS" "varchar"(50) NULL,
"ADDRESS2" "varchar"(50) NULL,
"ADDRESS3" "varchar"(50) NULL,
"TELP" "varchar"(12) NULL,
"LASTMODUSER" "varchar"(10) NULL,
"LASTMODDATE" "timestamp" NULL
)
CREATE TABLE "CATEGORY"(
"CTG" "varchar"(2) NULL,
"CTGNAME" "varchar"(37) NULL
)
CREATE view "viewterima" as select spbdarilogistik.nospb,spbdarilogistik.partno,spbdarilogistik.partname,spbdarilogistik.spbdate,spbdarilogistik.jumlah,spbdarilogistik.keterangan from spbdarilogistik
CREATE view "viewserahterima" as select serahkelogistik.nospb as nospbserah,serahkelogistik.partno as partnoserah,serahkelogistik.partname as partnameserah,serahkelogistik.nosp as nospserah,serahkelogistik.spbdate as spbdateserah,serahkelogistik.jumlah as jumlahserah,serahkelogistik.keterangan as ketserah, spbdarilogistik.nospb,spbdarilogistik.partno,spbdarilogistik.partname,spbdarilogistik.spbdate,spbdarilogistik.jumlah,spbdarilogistik.keterangan from serahkelogistik inner join spbdarilogistik on serahkelogistik.nospb=spbdarilogistik.nospb where spbdarilogistik.partno=''
CREATE view "viewserah" as select serahkelogistik.nospb as nospbserah,serahkelogistik.partno as partnoserah,serahkelogistik.partname as partnameserah,serahkelogistik.nosp as nospserah,serahkelogistik.spbdate as spbdateserah,serahkelogistik.jumlah as jumlahserah,serahkelogistik.keterangan as ketserah
from serahkelogistik
