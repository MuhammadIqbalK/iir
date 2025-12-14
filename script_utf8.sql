USE [master]
GO
/****** Object:  Database [QC]    Script Date: 11/23/2025 19:34:34 ******/
CREATE DATABASE [QC] ON  PRIMARY 
( NAME = N'QC_Data', FILENAME = N'c:\Program Files\Microsoft SQL Server\MSSQL10_50.SQLEXPRESS\MSSQL\DATA\QC.MDF' , SIZE = 67904KB , MAXSIZE = UNLIMITED, FILEGROWTH = 10%)
 LOG ON 
( NAME = N'QC_Log', FILENAME = N'c:\Program Files\Microsoft SQL Server\MSSQL10_50.SQLEXPRESS\MSSQL\DATA\QC_1.LDF' , SIZE = 3840KB , MAXSIZE = UNLIMITED, FILEGROWTH = 10%)
GO
ALTER DATABASE [QC] SET COMPATIBILITY_LEVEL = 80
GO
IF (1 = FULLTEXTSERVICEPROPERTY('IsFullTextInstalled'))
begin
EXEC [QC].[dbo].[sp_fulltext_database] @action = 'disable'
end
GO
ALTER DATABASE [QC] SET ANSI_NULL_DEFAULT OFF
GO
ALTER DATABASE [QC] SET ANSI_NULLS OFF
GO
ALTER DATABASE [QC] SET ANSI_PADDING OFF
GO
ALTER DATABASE [QC] SET ANSI_WARNINGS OFF
GO
ALTER DATABASE [QC] SET ARITHABORT OFF
GO
ALTER DATABASE [QC] SET AUTO_CLOSE ON
GO
ALTER DATABASE [QC] SET AUTO_CREATE_STATISTICS ON
GO
ALTER DATABASE [QC] SET AUTO_SHRINK ON
GO
ALTER DATABASE [QC] SET AUTO_UPDATE_STATISTICS ON
GO
ALTER DATABASE [QC] SET CURSOR_CLOSE_ON_COMMIT OFF
GO
ALTER DATABASE [QC] SET CURSOR_DEFAULT  GLOBAL
GO
ALTER DATABASE [QC] SET CONCAT_NULL_YIELDS_NULL OFF
GO
ALTER DATABASE [QC] SET NUMERIC_ROUNDABORT OFF
GO
ALTER DATABASE [QC] SET QUOTED_IDENTIFIER OFF
GO
ALTER DATABASE [QC] SET RECURSIVE_TRIGGERS OFF
GO
ALTER DATABASE [QC] SET  ENABLE_BROKER
GO
ALTER DATABASE [QC] SET AUTO_UPDATE_STATISTICS_ASYNC OFF
GO
ALTER DATABASE [QC] SET DATE_CORRELATION_OPTIMIZATION OFF
GO
ALTER DATABASE [QC] SET TRUSTWORTHY OFF
GO
ALTER DATABASE [QC] SET ALLOW_SNAPSHOT_ISOLATION OFF
GO
ALTER DATABASE [QC] SET PARAMETERIZATION SIMPLE
GO
ALTER DATABASE [QC] SET READ_COMMITTED_SNAPSHOT OFF
GO
ALTER DATABASE [QC] SET HONOR_BROKER_PRIORITY OFF
GO
ALTER DATABASE [QC] SET  READ_WRITE
GO
ALTER DATABASE [QC] SET RECOVERY SIMPLE
GO
ALTER DATABASE [QC] SET  MULTI_USER
GO
ALTER DATABASE [QC] SET PAGE_VERIFY TORN_PAGE_DETECTION
GO
ALTER DATABASE [QC] SET DB_CHAINING OFF
GO
USE [QC]
GO
/****** Object:  User [antony]    Script Date: 11/23/2025 19:34:34 ******/
CREATE USER [antony] WITHOUT LOGIN WITH DEFAULT_SCHEMA=[antony]
GO
/****** Object:  Schema [antony]    Script Date: 11/23/2025 19:34:34 ******/
CREATE SCHEMA [antony] AUTHORIZATION [antony]
GO
/****** Object:  Table [dbo].[USERLIST]    Script Date: 11/23/2025 19:34:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[USERLIST](
	[USERID] [nvarchar](50) NULL,
	[PASSWORD] [nvarchar](10) NULL,
	[FIRSTNAME] [nvarchar](50) NULL,
	[LASTNAME] [nvarchar](50) NULL,
	[GROUPID] [nvarchar](5) NULL,
	[STATUSLOGIN] [nvarchar](1) NULL,
	[LASTMODUSER] [nvarchar](10) NULL,
	[LASTMODDATE] [datetime] NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[urutscala]    Script Date: 11/23/2025 19:34:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[urutscala](
	[period] [varchar](6) NULL,
	[nsa] [decimal](18, 0) NULL,
	[sa] [decimal](18, 0) NULL
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[TREEMENU]    Script Date: 11/23/2025 19:34:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[TREEMENU](
	[STATUS] [char](1) NULL,
	[PARENT] [char](4) NULL,
	[KODEMENU] [char](4) NOT NULL,
	[NAMAMENU] [varchar](50) NULL,
	[ID] [decimal](18, 0) NULL,
	[NOU] [decimal](18, 0) NULL
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[TOTALBATCH]    Script Date: 11/23/2025 19:34:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[TOTALBATCH](
	[PARTNO] [varchar](17) NULL,
	[PARTNAME] [varchar](25) NULL,
	[SPEC] [varchar](50) NULL,
	[PRQTY] [float] NULL,
	[POQTY] [varchar](50) NULL,
	[UNIT] [varchar](3) NULL,
	[BATCHDATE] [datetime] NULL,
	[BATCHKE] [numeric](18, 0) NULL,
	[SUPPCODE] [varchar](7) NULL
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[TJAM]    Script Date: 11/23/2025 19:34:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[TJAM](
	[JAM] [smalldatetime] NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[tempo]    Script Date: 11/23/2025 19:34:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[tempo](
	[kode] [varchar](12) NULL,
	[nama] [varchar](50) NULL
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[tblUserGroup]    Script Date: 11/23/2025 19:34:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[tblUserGroup](
	[GroupID] [nvarchar](14) NULL,
	[GroupName] [nvarchar](50) NULL,
	[LastModUser] [nvarchar](10) NULL,
	[LastModDate] [datetime] NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[tblUser]    Script Date: 11/23/2025 19:34:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[tblUser](
	[USERID] [varchar](13) NOT NULL,
	[PASSWORD] [nvarchar](10) NULL,
	[FIRSTNAME] [nvarchar](50) NULL,
	[LASTNAME] [nvarchar](50) NULL,
	[GROUPID] [nvarchar](14) NULL,
	[STATUSLOGIN] [nvarchar](1) NULL,
	[MENUSECURITY] [varchar](400) NULL,
	[LastModUser] [nvarchar](10) NULL,
	[LastModDate] [datetime] NULL
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[tblTree]    Script Date: 11/23/2025 19:34:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[tblTree](
	[STATUS] [char](10) NULL,
	[PARENT] [char](10) NULL,
	[KODEMENU] [char](10) NOT NULL,
	[NAMAMENU] [varchar](50) NULL
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[tblParameter]    Script Date: 11/23/2025 19:34:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[tblParameter](
	[DONumberFlag] [nvarchar](1) NULL,
	[InvoiceMask] [nvarchar](3) NULL,
	[TransactionNo] [nvarchar](6) NULL,
	[PaymentTransMask] [nvarchar](1) NULL,
	[TransactionNoPayment] [nvarchar](5) NULL,
	[PriceInclTax] [nvarchar](1) NULL,
	[VAT] [nvarchar](2) NULL,
	[ReturnInvoiceMask] [nvarchar](3) NULL,
	[DOMask] [nvarchar](3) NULL,
	[SwapMask] [nvarchar](3) NULL,
	[ExpenseMask] [nvarchar](3) NULL,
	[A] [float] NULL,
	[B] [float] NULL,
	[C] [float] NULL,
	[D] [float] NULL,
	[E] [float] NULL,
	[F] [float] NULL,
	[G] [float] NULL,
	[H] [float] NULL,
	[I] [float] NULL,
	[J] [float] NULL,
	[K] [float] NULL,
	[L] [float] NULL,
	[M] [float] NULL,
	[N] [float] NULL,
	[O] [float] NULL,
	[P] [float] NULL,
	[Q] [float] NULL,
	[R] [float] NULL,
	[S] [float] NULL,
	[T] [float] NULL,
	[U] [float] NULL,
	[V] [float] NULL,
	[W] [float] NULL,
	[X] [float] NULL,
	[Y] [float] NULL,
	[Z] [float] NULL,
	[AA] [float] NULL,
	[BB] [float] NULL,
	[CC] [float] NULL,
	[DD] [float] NULL,
	[EE] [float] NULL,
	[CompanyCodes] [nvarchar](2) NULL,
	[CompanyName] [nvarchar](50) NULL,
	[ActiveYear] [nvarchar](2) NULL,
	[ValidCurr] [float] NULL,
	[LastDONumber] [int] NULL,
	[TTNo] [int] NULL,
	[digitleft] [int] NULL,
	[LastModUser] [nvarchar](10) NULL,
	[LastModDate] [datetime] NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[SUPPLIER]    Script Date: 11/23/2025 19:34:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[SUPPLIER](
	[SUPPCODE] [varchar](7) NOT NULL,
	[SUPPNAME] [varchar](50) NULL,
	[ADDRESS] [varchar](150) NULL,
	[TELP] [varchar](30) NULL,
	[FAX] [varchar](30) NULL,
	[TAX] [char](10) NULL,
	[ATTN] [varchar](50) NULL,
	[CREDIT] [float] NULL,
	[BLACKLIST] [bit] NULL,
	[PURCH] [varchar](1) NULL,
	[MINORDER] [decimal](18, 0) NULL,
	[LEAD] [decimal](18, 0) NULL
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[STOCK]    Script Date: 11/23/2025 19:34:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[STOCK](
	[PARTNO] [varchar](17) NOT NULL,
	[PARTNAME] [varchar](35) NULL,
	[SPEC] [varchar](30) NULL,
	[UNIT] [varchar](3) NULL,
	[CATEGORY] [varchar](1) NULL,
	[WHQ] [float] NULL,
	[WHIO] [float] NULL,
	[WHR] [float] NULL,
	[WHPRICE] [float] NULL,
	[IRQ] [float] NULL,
	[IRIP] [float] NULL,
	[IRR] [float] NULL,
	[IRPRICE] [float] NULL,
	[BLQ] [float] NULL,
	[BLIP] [float] NULL,
	[BLR] [float] NULL,
	[BLPRICE] [float] NULL,
	[TWQ] [float] NULL,
	[TWIP] [float] NULL,
	[TWR] [float] NULL,
	[TWPRICE] [float] NULL,
	[MIQ] [float] NULL,
	[MIIP] [float] NULL,
	[MIR] [float] NULL,
	[MIPRICE] [float] NULL,
	[SAQ] [float] NULL,
	[SAIP] [float] NULL,
	[SAR] [float] NULL,
	[SAPRICE] [float] NULL,
	[COMMON] [varchar](1) NULL,
	[TOTQTY] [float] NULL,
	[TOTPRICE] [float] NULL,
	[FIXED] [varchar](1) NULL,
	[LOCATION] [varchar](5) NULL,
	[MINSTOCK] [float] NULL,
	[REJECT] [float] NULL,
	[MAXSTOCK] [float] NULL,
 CONSTRAINT [PK_STOCK] PRIMARY KEY CLUSTERED 
(
	[PARTNO] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[status]    Script Date: 11/23/2025 19:34:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[status](
	[sta] [varchar](50) NULL
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[spbdarilogistik]    Script Date: 11/23/2025 19:34:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[spbdarilogistik](
	[partno] [varchar](17) NULL,
	[partname] [varchar](50) NULL,
	[nospb] [varchar](25) NULL,
	[spbdate] [datetime] NULL,
	[jumlah] [float] NULL,
	[keterangan] [varchar](100) NULL
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[serahkelogistik]    Script Date: 11/23/2025 19:34:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[serahkelogistik](
	[nospb] [varchar](25) NULL,
	[partno] [varchar](17) NULL,
	[partname] [varchar](50) NULL,
	[nosp] [varchar](25) NULL,
	[spbdate] [datetime] NULL,
	[jumlah] [float] NULL,
	[keterangan] [varchar](100) NULL
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[sementara]    Script Date: 11/23/2025 19:34:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[sementara](
	[suppcode] [varchar](7) NULL
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[semen]    Script Date: 11/23/2025 19:34:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[semen](
	[partno] [varchar](17) NULL,
	[partname] [varchar](50) NULL
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[REJECTHDR]    Script Date: 11/23/2025 19:34:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[REJECTHDR](
	[REJECTNO] [varchar](12) NOT NULL,
	[DATEREJECT] [datetime] NULL,
	[WEEKDAY] [varchar](6) NULL,
	[DIVISION] [varchar](5) NULL,
	[PROCESSBY] [varchar](50) NULL,
	[PRODID] [varchar](15) NULL,
	[PRODQTY] [decimal](18, 0) NULL
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[REJECTDTL]    Script Date: 11/23/2025 19:34:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[REJECTDTL](
	[REJECTNO] [varchar](12) NULL,
	[PARTNO] [varchar](16) NULL,
	[PARTNAME] [varchar](50) NULL,
	[SPEC] [varchar](50) NULL,
	[UNIT] [varchar](3) NULL,
	[QTY] [float] NULL,
	[REMARKS] [varchar](100) NULL,
	[CTG] [varchar](2) NULL,
	[qtyop] [float] NULL,
	[qtym] [float] NULL,
	[qtyqc] [float] NULL
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[PO]    Script Date: 11/23/2025 19:34:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[PO](
	[PONO] [varchar](15) NOT NULL,
	[PODATE] [datetime] NULL,
	[PRNO] [varchar](15) NULL,
	[SUPPCODE] [varchar](7) NULL,
	[ATTN] [varchar](20) NULL,
	[WEEKNEED] [varchar](6) NULL,
	[TOPS] [float] NULL,
	[SHIPMENT] [varchar](40) NULL,
	[NOTES] [varchar](100) NULL,
	[TAX] [int] NULL,
	[DISCOUNT1] [float] NULL,
	[POTYPE] [char](1) NULL,
	[STATUS] [varchar](1) NULL,
	[PURCH] [varchar](1) NULL,
	[PRSSTS] [varchar](1) NULL,
	[DELDATE] [datetime] NULL
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[pemeriksa_MST]    Script Date: 11/23/2025 19:34:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[pemeriksa_MST](
	[nama] [varchar](50) NULL
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[pemeriksa]    Script Date: 11/23/2025 19:34:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[pemeriksa](
	[nama] [varchar](50) NULL
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[PARAMETER]    Script Date: 11/23/2025 19:34:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[PARAMETER](
	[PRMASKINGSHM] [varchar](4) NULL,
	[PRMASKING] [varchar](4) NULL,
	[PRMASKINGIMP] [varchar](4) NULL,
	[POMASKINGSHM] [varchar](4) NULL,
	[POMASKING] [varchar](4) NULL,
	[RGOODSMASKING] [varchar](4) NULL,
	[RGOODSMASKINGSHM] [varchar](4) NULL,
	[RGOODSMASKINGIMP] [varchar](4) NULL,
	[COMPANYCODES] [char](2) NULL,
	[COMPANYNAMES] [varchar](50) NULL,
	[ACTIVEPERIOD] [varchar](6) NULL,
	[RGISOMASKING] [varchar](4) NULL,
	[ACTIVEDATE] [datetime] NULL,
	[SERVERNAME] [varchar](50) NOT NULL
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[MTVREPORT]    Script Date: 11/23/2025 19:34:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[MTVREPORT](
	[PERIOD] [varchar](6) NULL,
	[PARTNO] [varchar](16) NULL,
	[RG] [float] NULL,
	[MTV] [float] NULL,
	[TSM] [float] NULL,
	[ADJ] [float] NULL,
	[RTL] [float] NULL,
	[RGR] [float] NULL,
	[PR] [float] NULL,
	[RJT] [float] NULL
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[MATERIALCATEGORY]    Script Date: 11/23/2025 19:34:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[MATERIALCATEGORY](
	[CATEGORYID] [int] NOT NULL,
	[DESCRIPTION] [varchar](50) NULL
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[lokalimport]    Script Date: 11/23/2025 19:34:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[lokalimport](
	[totimport] [numeric](18, 0) NULL,
	[totlokalY] [numeric](18, 0) NULL,
	[totlokalN] [numeric](18, 0) NULL,
	[tgl1] [datetime] NULL,
	[tgl2] [datetime] NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[libur]    Script Date: 11/23/2025 19:34:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[libur](
	[period] [varchar](6) NULL,
	[n] [numeric](18, 0) NULL
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[lagi]    Script Date: 11/23/2025 19:34:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[lagi](
	[JAM] [smalldatetime] NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[IIRIMPORT]    Script Date: 11/23/2025 19:34:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[IIRIMPORT](
	[TGLMASUK] [datetime] NULL,
	[PARTNO] [varchar](17) NULL,
	[PARTNAME] [varchar](50) NULL,
	[JUMLAH] [numeric](18, 0) NULL,
	[SAMPLING] [numeric](18, 0) NULL,
	[TGLMULAI] [datetime] NULL,
	[TGLSELESAI] [datetime] NULL,
	[LAMABATCH] [numeric](18, 0) NULL,
	[LAMAPART] [numeric](18, 0) NULL,
	[KETERANGAN] [char](10) NULL,
	[NODOC] [varchar](50) NULL,
	[jumlibur] [numeric](18, 0) NULL
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[IIRb]    Script Date: 11/23/2025 19:34:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[IIRb](
	[batchdate] [datetime] NULL,
	[suppcode] [varchar](7) NULL,
	[partno] [varchar](17) NULL,
	[partname] [varchar](50) NULL,
	[nodoc] [varchar](25) NULL,
	[batchsize] [float] NULL,
	[simplesize] [float] NULL,
	[levela] [decimal](18, 0) NULL,
	[aql] [float] NULL,
	[status] [varchar](25) NULL,
	[pemeriksa] [varchar](50) NULL,
	[start] [datetime] NULL,
	[finish] [datetime] NULL,
	[duration] [varchar](50) NULL,
	[iirdate] [datetime] NULL,
	[batchke] [decimal](18, 0) NULL,
	[untuk] [varchar](12) NULL,
	[tglmulai] [datetime] NULL,
	[tglselesai] [datetime] NULL,
	[lamapart] [decimal](18, 0) NULL,
	[jumlibur] [decimal](18, 0) NULL
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[IIR]    Script Date: 11/23/2025 19:34:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[IIR](
	[batchdate] [datetime] NULL,
	[suppcode] [varchar](7) NULL,
	[partno] [varchar](17) NULL,
	[partname] [varchar](50) NULL,
	[nodoc] [varchar](25) NULL,
	[batchsize] [float] NULL,
	[simplesize] [float] NULL,
	[levela] [numeric](18, 0) NULL,
	[aql] [float] NULL,
	[status] [varchar](25) NULL,
	[pemeriksa] [varchar](50) NULL,
	[start] [datetime] NULL,
	[finish] [datetime] NULL,
	[duration] [varchar](50) NULL,
	[iirdate] [datetime] NULL,
	[batchke] [numeric](18, 0) NULL,
	[untuk] [varchar](12) NULL,
	[tglmulai] [datetime] NULL,
	[tglselesai] [datetime] NULL,
	[lamapart] [numeric](18, 0) NULL,
	[jumlibur] [numeric](18, 0) NULL
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[DIVISION]    Script Date: 11/23/2025 19:34:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[DIVISION](
	[DIVCD] [varchar](2) NULL,
	[DIVNM] [varchar](50) NULL,
	[CHIEFNM] [varchar](30) NULL
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[CUSTOMER]    Script Date: 11/23/2025 19:34:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[CUSTOMER](
	[CUSTCODE] [varchar](15) NOT NULL,
	[CUSTNAME] [varchar](50) NULL,
	[ADDRESS1] [varchar](50) NULL,
	[ADDRESS2] [varchar](50) NULL,
	[NPWP] [varchar](15) NULL,
	[PKP] [float] NULL,
	[TELP1] [varchar](30) NULL,
	[FAX1] [varchar](30) NULL
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[CUSTCOUNTER]    Script Date: 11/23/2025 19:34:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[CUSTCOUNTER](
	[A] [int] NULL,
	[B] [int] NULL,
	[C] [int] NULL,
	[D] [int] NULL,
	[E] [int] NULL,
	[F] [int] NULL,
	[G] [int] NULL,
	[H] [int] NULL,
	[I] [int] NULL,
	[J] [int] NULL,
	[K] [int] NULL,
	[L] [int] NULL,
	[M] [int] NULL,
	[N] [int] NULL,
	[O] [int] NULL,
	[P] [int] NULL,
	[Q] [int] NULL,
	[R] [int] NULL,
	[S] [int] NULL,
	[T] [int] NULL,
	[U] [int] NULL,
	[V] [int] NULL,
	[W] [int] NULL,
	[X] [int] NULL,
	[Y] [int] NULL,
	[Z] [int] NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[COUNTER]    Script Date: 11/23/2025 19:34:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[COUNTER](
	[PERIOD] [varchar](6) NULL,
	[PONO] [float] NULL,
	[POSHM] [float] NULL,
	[PRIMP] [float] NULL,
	[PRCNO1] [float] NULL,
	[PRCNO2] [float] NULL,
	[PRSHM] [float] NULL,
	[RGNO] [float] NULL,
	[RGNOSHM] [float] NULL,
	[RGRNO] [float] NULL,
	[RTRNO] [float] NULL,
	[DONO] [float] NULL,
	[PRONO] [float] NULL,
	[RJTNO] [float] NULL,
	[RQSIR] [float] NULL,
	[RQSBL] [float] NULL,
	[RQSTW] [float] NULL,
	[RQSWH] [float] NULL,
	[RQSSA] [float] NULL,
	[RJTIR] [float] NULL,
	[RJTBL] [float] NULL,
	[RJTTW] [float] NULL,
	[RJTWH] [float] NULL,
	[RJTSA] [float] NULL,
	[MAINTID] [float] NULL,
	[DOWNID] [float] NULL,
	[PRODID] [float] NULL,
	[RETID] [float] NULL,
	[RGISO] [float] NULL,
	[POIMP] [float] NULL,
	[RGIMP] [float] NULL,
	[ROT] [float] NULL,
	[RIN] [float] NULL,
	[RQSMI] [float] NULL,
	[RJTMI] [float] NULL,
	[RQSAC] [float] NULL,
	[RJTAC] [float] NULL,
	[RJTNNO] [float] NULL
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[COMPANYPARAMETER]    Script Date: 11/23/2025 19:34:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[COMPANYPARAMETER](
	[BRANCHCODE] [nvarchar](10) NULL,
	[BRANCHNAME] [nvarchar](50) NULL,
	[ADDRESS] [nvarchar](50) NULL,
	[ADDRESS2] [nvarchar](50) NULL,
	[ADDRESS3] [nvarchar](50) NULL,
	[TELP] [nvarchar](12) NULL,
	[LASTMODUSER] [nvarchar](10) NULL,
	[LASTMODDATE] [datetime] NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[CATEGORY]    Script Date: 11/23/2025 19:34:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[CATEGORY](
	[CTG] [varchar](2) NULL,
	[CTGNAME] [varchar](37) NULL
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  View [dbo].[viewterima]    Script Date: 11/23/2025 19:34:37 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE view [dbo].[viewterima] as select spbdarilogistik.nospb,spbdarilogistik.partno,spbdarilogistik.partname,spbdarilogistik.spbdate,spbdarilogistik.jumlah,spbdarilogistik.keterangan from spbdarilogistik
GO
/****** Object:  View [dbo].[viewserahterima]    Script Date: 11/23/2025 19:34:37 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE view [dbo].[viewserahterima] as select serahkelogistik.nospb as nospbserah,serahkelogistik.partno as partnoserah,serahkelogistik.partname as partnameserah,serahkelogistik.nosp as nospserah,serahkelogistik.spbdate as spbdateserah,serahkelogistik.jumlah as jumlahserah,serahkelogistik.keterangan as ketserah, spbdarilogistik.nospb,spbdarilogistik.partno,spbdarilogistik.partname,spbdarilogistik.spbdate,spbdarilogistik.jumlah,spbdarilogistik.keterangan from serahkelogistik inner join spbdarilogistik on serahkelogistik.nospb=spbdarilogistik.nospb where spbdarilogistik.partno=''
GO
/****** Object:  View [dbo].[viewserah]    Script Date: 11/23/2025 19:34:37 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE view [dbo].[viewserah] as select serahkelogistik.nospb as nospbserah,serahkelogistik.partno as partnoserah,serahkelogistik.partname as partnameserah,serahkelogistik.nosp as nospserah,serahkelogistik.spbdate as spbdateserah,serahkelogistik.jumlah as jumlahserah,serahkelogistik.keterangan as ketserah
from serahkelogistik
GO
