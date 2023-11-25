-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 22, 2023 at 08:51 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `basicdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `additional_setting`
--

CREATE TABLE `additional_setting` (
  `return_period` smallint(2) DEFAULT 3,
  `due_date_period` smallint(2) DEFAULT 2,
  `stock_planning_reference` smallint(2) DEFAULT 4,
  `member_point_multiply` int(11) NOT NULL DEFAULT 0,
  `is_member_point` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `additional_setting`
--

INSERT INTO `additional_setting` (`return_period`, `due_date_period`, `stock_planning_reference`, `member_point_multiply`, `is_member_point`) VALUES
(3, 2, 4, 1000, 1);

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `default_pos_printer` varchar(150) DEFAULT NULL,
  `store_name` varchar(100) DEFAULT NULL,
  `store_address` varchar(100) DEFAULT NULL,
  `store_city` varchar(100) DEFAULT NULL,
  `store_phone` varchar(50) DEFAULT NULL,
  `store_registration` varchar(50) DEFAULT NULL,
  `store_alias` varchar(4) DEFAULT NULL,
  `base_url` varchar(100) DEFAULT NULL,
  `return_period` smallint(3) DEFAULT NULL,
  `due_date_period` smallint(3) DEFAULT NULL,
  `default_pharmacist` varchar(100) DEFAULT NULL,
  `default_pharmacist_registration` varchar(100) DEFAULT NULL,
  `paper_size` int(3) DEFAULT NULL,
  `is_print_prescription` tinyint(1) DEFAULT NULL,
  `is_print_sales_receipt` tinyint(1) DEFAULT NULL,
  `is_dot_matrix` tinyint(1) DEFAULT NULL,
  `dot_matrix_paper_size` varchar(15) DEFAULT NULL,
  `last_coded` datetime DEFAULT NULL,
  `total_column` tinyint(1) DEFAULT NULL,
  `label_width` smallint(2) DEFAULT NULL,
  `label_height` smallint(2) DEFAULT NULL,
  `left_margin` smallint(2) DEFAULT NULL,
  `top_margin` smallint(2) DEFAULT NULL,
  `bottom_margin` smallint(2) DEFAULT NULL,
  `label_horizontal_margin` smallint(2) DEFAULT NULL,
  `receipt_header` varchar(500) DEFAULT NULL,
  `receipt_footer` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`default_pos_printer`, `store_name`, `store_address`, `store_city`, `store_phone`, `store_registration`, `store_alias`, `base_url`, `return_period`, `due_date_period`, `default_pharmacist`, `default_pharmacist_registration`, `paper_size`, `is_print_prescription`, `is_print_sales_receipt`, `is_dot_matrix`, `dot_matrix_paper_size`, `last_coded`, `total_column`, `label_width`, `label_height`, `left_margin`, `top_margin`, `bottom_margin`, `label_horizontal_margin`, `receipt_header`, `receipt_footer`) VALUES
(NULL, 'APOTIK SIMAP', 'JL. SIGURA-GURA BARAT', 'MALANG', '0341-1234567', NULL, 'APS', 'http://localhost:8080', NULL, NULL, 'N/A', 'N/A', 80, 1, 1, 0, '22.59CM 13.97CM', NULL, 1, 50, 20, 10, 10, 10, 10, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `shift`
--

CREATE TABLE `shift` (
  `id` int(10) UNSIGNED NOT NULL,
  `start_time` smallint(2) DEFAULT NULL,
  `end_time` smallint(2) DEFAULT NULL,
  `createddate` timestamp NULL DEFAULT current_timestamp(),
  `createdby` int(10) UNSIGNED NOT NULL,
  `updateddate` datetime DEFAULT NULL,
  `updatedby` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `shift`
--

INSERT INTO `shift` (`id`, `start_time`, `end_time`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES
(1, 6, 14, '2023-01-01 00:00:00', 1, NULL, 1),
(2, 14, 22, '2023-01-01 00:00:00', 1, NULL, 1),
(3, 22, 6, '2023-01-01 00:00:00', 1, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(20) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `sex` varchar(1) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `id_no` varchar(20) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `pharmacist_registration` varchar(50) DEFAULT NULL,
  `pharmacist_privilege` varchar(45) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `createddate` timestamp NULL DEFAULT current_timestamp(),
  `createdby` int(10) UNSIGNED DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL,
  `updatedby` int(10) UNSIGNED DEFAULT NULL,
  `language` varchar(2) DEFAULT 'en',
  `menu_access` text DEFAULT NULL,
  `html_access` text DEFAULT NULL,
  `table_prefix` varchar(45) DEFAULT NULL,
  `is_authority` tinyint(1) DEFAULT NULL,
  `is_prec_psyc_report` tinyint(1) DEFAULT 0,
  `is_sales_rebate` tinyint(1) DEFAULT 0,
  `is_due_date_notification` tinyint(1) DEFAULT 0,
  `is_credit_sales` tinyint(1) DEFAULT 0,
  `is_credit_sales_notification` tinyint(1) DEFAULT 0,
  `is_sales_approval` tinyint(1) DEFAULT 0,
  `is_full_daily_sales` tinyint(1) DEFAULT 0,
  `is_price_verification_notification` tinyint(1) DEFAULT 0,
  `has_access_dashboard` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `is_new_password` tinyint(1) DEFAULT 0,
  `is_super_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `name`, `sex`, `birthday`, `id_no`, `address`, `city`, `phone`, `pharmacist_registration`, `pharmacist_privilege`, `last_login`, `createddate`, `createdby`, `updateddate`, `updatedby`, `language`, `menu_access`, `html_access`, `table_prefix`, `is_authority`, `is_prec_psyc_report`, `is_sales_rebate`, `is_due_date_notification`, `is_credit_sales`, `is_credit_sales_notification`, `is_sales_approval`, `is_full_daily_sales`, `is_price_verification_notification`, `has_access_dashboard`, `is_active`, `is_new_password`, `is_super_admin`) VALUES
(1, 'ADMIN', '319f4d26e3c536b5dd871bb2c52e3178', 'SUPERADMIN', 'P', '1990-01-01', '1234567890', 'MALANG', 'MALANG', '12345', NULL, NULL, '2023-08-03 00:38:21', '2023-03-27 17:38:30', 1, '2023-08-22 12:58:04', 1, 'en', 'sales.1,sales_receipt.2,prescription_receipt.1,sales_return.1,promotion.2,cash_opname_submission.2,cash_opname.1,patient.2,member.2,medical_record.1,doctor.2,counseling.2,stock_card.1,stock_adjustment.2,stock_opname.2,stock_position.1,network_stock.1,network_return_examination.1,network_transfer.2,network_return_compliance.1,network_transfer_examination.1,network_transfer_compliance.2,network_compliance_return.2,network_branch.2,purchase_order.2,order_reception.2,payment_receipt.2,purchase_return.1,stock_planning.1,consignment_receipt.2,consignment_payment.1,consignment_payment_history.1,consignment_return.1,report/daily_sales.1,report/sales.1,report/purchase.1,report/sales_return.1,report/purchase_return.1,additional_report/price_adjustment.1,additional_report/commission.1,additional_report/price_movement.1,additional_report/stock_movement.1,report/payable_aging.1,report/payable_payment.1,report/receivable_aging.1,report/receivable_payment.1,report/product.1,report/password_reset.1,customer_area.2,bank.2,distributor.2,medication_type.2,shift.2,partner.2,customer_group.2,user.2,category.2,brand.2,product.2,medication_package.2,rack.2,emballage.2,instruction_frequency.2,instruction_route.2,base_unit.2,purchase_unit.2,sales_unit.2,dosage_unit.2,target.2,backup_restore.1,acc_journal_voucher.2,acc_cash_reception.2,acc_cash_transfer.2,acc_cash_disbursement.2,acc_closed_period.2,acc_account_group.2,acc_account.2,acc_account_map.2,acc_report/ledger.1,acc_report/general_ledger.1,acc_report/trial_balance.1,acc_report/profit_loss.1,acc_report/balance_sheet.1,price_adjustment_verification.2,setting.1,additional_setting.1', '\r\n      <div id=\'simap_logo-container\'>\r\n        <a href=\'http://localhost:8080\'><img src=\'http://localhost:8080/files/img/properties/simap_logo.png\' id=\'simap_logo\'/></a>\r\n      </div>\r\n      <div id=\'simap_menu-container\'>\r\n      <ul id=\'simap_menu\'>\r\n    <li><b><span class=\"fa fa-shopping-bag\"></span> <span>PENJUALAN</span></b><ul><li><a href=\'http://localhost:8080/sales\'>Penjualan</a></li><li><a href=\'http://localhost:8080/sales_receipt\'>Nota Penjualan</a></li><li><a href=\'http://localhost:8080/prescription_receipt\'>Nota Resep</a></li><li><a href=\'http://localhost:8080/sales_return\'>Retur Penjualan</a></li><li><a href=\'http://localhost:8080/promotion\'>Promosi</a></li><li><a href=\'http://localhost:8080/cash_opname_submission\'>Pengajuan Opname Kas</a></li><li><a href=\'http://localhost:8080/cash_opname\'>Data Opname Kas</a></li></ul></li><li><b><span class=\"fa fa-user\"></span> <span>PELANGGAN</span></b><ul><li><a href=\'http://localhost:8080/patient\'>Pasien</a></li><li><a href=\'http://localhost:8080/member\'>Member</a></li></ul></li><li><b><span class=\"fa fa-clinic-medical\"></span> <span>RAWAT JALAN</span></b><ul><li><a href=\'http://localhost:8080/medical_record\'>Rekam Medis</a></li><li><a href=\'http://localhost:8080/doctor\'>Dokter</a></li><li><a href=\'http://localhost:8080/counseling\'>KIE</a></li></ul></li><li><b><span class=\"fa fa-archive\"></span> <span>STOK</span></b><ul><li><a href=\'http://localhost:8080/stock_card\'>Kartu Stok</a></li><li><a href=\'http://localhost:8080/stock_adjustment\'>Penyesuaian Stok</a></li><li><a href=\'http://localhost:8080/stock_opname\'>Stock Opname</a></li><li><a href=\'http://localhost:8080/stock_position\'>Posisi Stok</a></li></ul></li><li><b><span class=\"fa fa-globe\"></span> <span>JARINGAN</span></b><ul><li><a href=\'http://localhost:8080/network_stock\'>Stok Cabang</a></li><li><a href=\'http://localhost:8080/network_return_examination\'>Penerimaan Retur Mutasi</a></li><li><a href=\'http://localhost:8080/network_transfer\'>Mutasi Keluar</a></li><li><a href=\'http://localhost:8080/network_return_compliance\'>Retur Mutasi Keluar</a></li><li><a href=\'http://localhost:8080/network_transfer_examination\'>Penerimaan Mutasi</a></li><li><a href=\'http://localhost:8080/network_transfer_compliance\'>Mutasi Masuk</a></li><li><a href=\'http://localhost:8080/network_compliance_return\'>Retur Mutasi Masuk</a></li><li><a href=\'http://localhost:8080/network_branch\'>Data Cabang</a></li></ul></li><li><b><span class=\"fa fa-truck\"></span> <span>PENGADAAN BARANG</span></b><ul><li><a href=\'http://localhost:8080/purchase_order\'>Surat Pesanan</a></li><li><a href=\'http://localhost:8080/order_reception\'>Penerimaan Pesanan</a></li><li><a href=\'http://localhost:8080/payment_receipt\'>Nota Pembelian</a></li><li><a href=\'http://localhost:8080/purchase_return\'>Retur Pembelian</a></li><li><a href=\'http://localhost:8080/stock_planning\'>Perencanaan Stok</a></li><li><a href=\'http://localhost:8080/consignment_receipt\'>Penerimaan Konsinyasi</a></li><li><a href=\'http://localhost:8080/consignment_payment\'>Pembayaran Konsinyasi</a></li><li><a href=\'http://localhost:8080/consignment_payment_history\'>Nota Pembayaran Konsinyasi</a></li><li><a href=\'http://localhost:8080/consignment_return\'>Retur Konsinyasi</a></li></ul></li><li><b><span class=\"fa fa-clipboard\"></span> <span>LAPORAN</span></b><ul><li><a href=\'http://localhost:8080/report/daily_sales\'>Penjualan Per Jam Kerja</a></li><li><a href=\'http://localhost:8080/report/sales\'>Penjualan</a></li><li><a href=\'http://localhost:8080/report/purchase\'>Pembelian</a></li><li><a href=\'http://localhost:8080/report/sales_return\'>Retur Penjualan</a></li><li><a href=\'http://localhost:8080/report/purchase_return\'>Retur Pembelian</a></li><li><a href=\'http://localhost:8080/additional_report/price_adjustment\'>Pembaharuan Harga</a></li><li><a href=\'http://localhost:8080/additional_report/commission\'>Komisi</a></li><li><a href=\'http://localhost:8080/additional_report/price_movement\'>Pergerakan Harga</a></li><li><a href=\'http://localhost:8080/additional_report/stock_movement\'>Pergerakan Stok</a></li><li><a href=\'http://localhost:8080/report/payable_aging\'>Umur Hutang</a></li><li><a href=\'http://localhost:8080/report/payable_payment\'>Pembayaran Hutang</a></li><li><a href=\'http://localhost:8080/report/receivable_aging\'>Umur Piutang</a></li><li><a href=\'http://localhost:8080/report/receivable_payment\'>Pembayaran Piutang</a></li><li><a href=\'http://localhost:8080/report/product\'>Produk</a></li><li><a href=\'http://localhost:8080/report/password_reset\'>Reset Sandi</a></li></ul></li><li><b><span class=\"fa fa-database\"></span> <span>MASTER</span></b><ul><li><a href=\'http://localhost:8080/customer_area\'>Area Pelanggan</a></li><li><a href=\'http://localhost:8080/bank\'>Bank</a></li><li><a href=\'http://localhost:8080/distributor\'>Distributor</a></li><li><a href=\'http://localhost:8080/medication_type\'>Golongan Produk</a></li><li><a href=\'http://localhost:8080/shift\'>Jam Kerja</a></li><li><a href=\'http://localhost:8080/partner\'>Rekanan</a></li><li><a href=\'http://localhost:8080/customer_group\'>Jenis Pelanggan</a></li><li><a href=\'http://localhost:8080/user\'>Karyawan & Akses</a></li><li><a href=\'http://localhost:8080/category\'>Kategori Produk</a></li><li><a href=\'http://localhost:8080/brand\'>Merek Produk</a></li><li><a href=\'http://localhost:8080/product\'>Produk</a></li><li><a href=\'http://localhost:8080/medication_package\'>Paket Medikasi</a></li><li><a href=\'http://localhost:8080/rack\'>Rak Produk</a></li><li><a href=\'http://localhost:8080/emballage\'>Resep - Embalase</a></li><li><a href=\'http://localhost:8080/instruction_frequency\'>Resep - Frekuensi Pemberian</a></li><li><a href=\'http://localhost:8080/instruction_route\'>Resep - Rute Pemberian</a></li><li><a href=\'http://localhost:8080/base_unit\'>Satuan Dasar</a></li><li><a href=\'http://localhost:8080/purchase_unit\'>Satuan Beli</a></li><li><a href=\'http://localhost:8080/sales_unit\'>Satuan Jual</a></li><li><a href=\'http://localhost:8080/dosage_unit\'>Satuan Dosis</a></li><li><a href=\'http://localhost:8080/target\'>Target Bulanan</a></li><li><a href=\'http://localhost:8080/backup_restore\'>Backup & Restore</a></li></ul></li><li><b><span class=\"fa fa-book\"></span> <span>AKUNTANSI</span></b><ul><li><a href=\'http://localhost:8080/acc_journal_voucher\'>Jurnal Voucher</a></li><li><a href=\'http://localhost:8080/acc_cash_reception\'>Penerimaan Kas</a></li><li><a href=\'http://localhost:8080/acc_cash_transfer\'>Perpindahan Antar Kas</a></li><li><a href=\'http://localhost:8080/acc_cash_disbursement\'>Pengeluaran Kas</a></li><li><a href=\'http://localhost:8080/acc_closed_period\'>Penutupan Periode</a></li><li><a href=\'http://localhost:8080/acc_account_group\'>Kelompok Akun</a></li><li><a href=\'http://localhost:8080/acc_account\'>Daftar Akun</a></li><li><a href=\'http://localhost:8080/acc_account_map\'>Pemetaan Akun</a></li><li><a href=\'http://localhost:8080/acc_report/ledger\'>Laporan Jurnal</a></li><li><a href=\'http://localhost:8080/acc_report/general_ledger\'>Laporan Buku Besar</a></li><li><a href=\'http://localhost:8080/acc_report/trial_balance\'>Laporan Neraca Saldo</a></li><li><a href=\'http://localhost:8080/acc_report/profit_loss\'>Laporan Laba Rugi</a></li><li><a href=\'http://localhost:8080/acc_report/balance_sheet\'>Laporan Neraca</a></li></ul></li><li><a href=\'http://localhost:8080/price_adjustment_verification\'><b><span class=\"fa fa-tasks\"></span> <span>VERIFIKASI</span></b></a></li><li><b><span class=\"fa fa-cogs\"></span> <span>PENGATURAN</span></b><ul><li><a href=\'http://localhost:8080/setting\'>Teknis</a></li><li><a href=\'http://localhost:8080/additional_setting\'>Umum</a></li></ul></li></ul></div>', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `shift`
--
ALTER TABLE `shift`
  ADD PRIMARY KEY (`id`,`updatedby`,`createdby`),
  ADD KEY `fk_shift_user1_idx` (`createdby`),
  ADD KEY `fk_shift_user2_idx` (`updatedby`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_1_idx` (`createdby`),
  ADD KEY `fk_user_2_idx` (`updatedby`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `shift`
--
ALTER TABLE `shift`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `shift`
--
ALTER TABLE `shift`
  ADD CONSTRAINT `fk_shift_user1` FOREIGN KEY (`createdby`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_shift_user2` FOREIGN KEY (`updatedby`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_1` FOREIGN KEY (`createdby`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_user_2` FOREIGN KEY (`updatedby`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
