/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50726
Source Host           : localhost:3306
Source Database       : aiyara_db

Target Server Type    : MYSQL
Target Server Version : 50726
File Encoding         : 65001

Date: 2020-10-27 13:06:03
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `businessweb`
-- ----------------------------
DROP TABLE IF EXISTS `businessweb`;
CREATE TABLE `businessweb` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `topic` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `content` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of businessweb
-- ----------------------------
INSERT INTO `businessweb` VALUES ('1', 'ชื่อหัวข้อ1', 'local/public/businessweb/', '1603108791.jpg', 'เนื้อหา 1', '2020-10-19 18:59:51', '2020-10-19 18:59:51', null);
INSERT INTO `businessweb` VALUES ('2', 'ชื่อหัวข้อ2', 'local/public/businessweb/', 'img02.jpg', 'เนื้อหา 2', '2020-10-19 13:51:11', '2020-10-19 13:51:11', null);
INSERT INTO `businessweb` VALUES ('3', 'ชื่อหัวข้อ3', 'local/public/businessweb/', 'img03.jpg', 'เนื้อหา 3', '2020-10-19 14:38:15', '2020-10-19 14:38:15', null);

-- ----------------------------
-- Table structure for `businessweb_banner`
-- ----------------------------
DROP TABLE IF EXISTS `businessweb_banner`;
CREATE TABLE `businessweb_banner` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `businessweb_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>businessweb>id',
  `image_path` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=105 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of businessweb_banner
-- ----------------------------
INSERT INTO `businessweb_banner` VALUES ('1', '1', 'local/public/businessweb/', 'b101.jpg', '2020-10-19 11:22:09', null, null);
INSERT INTO `businessweb_banner` VALUES ('2', '1', 'local/public/businessweb/', 'b102.jpg', '2020-10-19 11:22:09', null, null);
INSERT INTO `businessweb_banner` VALUES ('3', '1', 'local/public/businessweb/', 'b103.jpg', '2020-10-19 11:22:09', null, null);
INSERT INTO `businessweb_banner` VALUES ('36', null, null, null, '2020-10-19 18:00:45', '2020-10-19 18:00:45', null);
INSERT INTO `businessweb_banner` VALUES ('22', '2', 'local/public/businessweb/', 'b1603105184.png', '2020-10-19 17:59:44', '2020-10-19 17:59:44', null);
INSERT INTO `businessweb_banner` VALUES ('23', null, null, null, '2020-10-19 18:00:43', '2020-10-19 18:00:43', null);
INSERT INTO `businessweb_banner` VALUES ('24', null, null, null, '2020-10-19 18:00:44', '2020-10-19 18:00:44', null);
INSERT INTO `businessweb_banner` VALUES ('25', null, null, null, '2020-10-19 18:00:44', '2020-10-19 18:00:44', null);
INSERT INTO `businessweb_banner` VALUES ('26', null, null, null, '2020-10-19 18:00:44', '2020-10-19 18:00:44', null);
INSERT INTO `businessweb_banner` VALUES ('27', null, null, null, '2020-10-19 18:00:44', '2020-10-19 18:00:44', null);
INSERT INTO `businessweb_banner` VALUES ('28', null, null, null, '2020-10-19 18:00:44', '2020-10-19 18:00:44', null);
INSERT INTO `businessweb_banner` VALUES ('29', null, null, null, '2020-10-19 18:00:44', '2020-10-19 18:00:44', null);
INSERT INTO `businessweb_banner` VALUES ('30', null, null, null, '2020-10-19 18:00:44', '2020-10-19 18:00:44', null);
INSERT INTO `businessweb_banner` VALUES ('31', null, null, null, '2020-10-19 18:00:44', '2020-10-19 18:00:44', null);
INSERT INTO `businessweb_banner` VALUES ('32', null, null, null, '2020-10-19 18:00:44', '2020-10-19 18:00:44', null);
INSERT INTO `businessweb_banner` VALUES ('33', null, null, null, '2020-10-19 18:00:44', '2020-10-19 18:00:44', null);
INSERT INTO `businessweb_banner` VALUES ('34', null, null, null, '2020-10-19 18:00:45', '2020-10-19 18:00:45', null);
INSERT INTO `businessweb_banner` VALUES ('35', null, null, null, '2020-10-19 18:00:45', '2020-10-19 18:00:45', null);
INSERT INTO `businessweb_banner` VALUES ('37', null, null, null, '2020-10-19 18:00:45', '2020-10-19 18:00:45', null);
INSERT INTO `businessweb_banner` VALUES ('38', null, null, null, '2020-10-19 18:00:45', '2020-10-19 18:00:45', null);
INSERT INTO `businessweb_banner` VALUES ('39', null, null, null, '2020-10-19 18:00:45', '2020-10-19 18:00:45', null);
INSERT INTO `businessweb_banner` VALUES ('40', null, null, null, '2020-10-19 18:00:45', '2020-10-19 18:00:45', null);
INSERT INTO `businessweb_banner` VALUES ('41', null, null, null, '2020-10-19 18:00:45', '2020-10-19 18:00:45', null);
INSERT INTO `businessweb_banner` VALUES ('42', null, null, null, '2020-10-19 18:00:45', '2020-10-19 18:00:45', null);
INSERT INTO `businessweb_banner` VALUES ('43', null, null, null, '2020-10-19 18:00:47', '2020-10-19 18:00:47', null);
INSERT INTO `businessweb_banner` VALUES ('44', null, null, null, '2020-10-19 18:00:47', '2020-10-19 18:00:47', null);
INSERT INTO `businessweb_banner` VALUES ('45', null, null, null, '2020-10-19 18:00:47', '2020-10-19 18:00:47', null);
INSERT INTO `businessweb_banner` VALUES ('46', null, null, null, '2020-10-19 18:00:47', '2020-10-19 18:00:47', null);
INSERT INTO `businessweb_banner` VALUES ('47', null, null, null, '2020-10-19 18:00:47', '2020-10-19 18:00:47', null);
INSERT INTO `businessweb_banner` VALUES ('48', null, null, null, '2020-10-19 18:00:47', '2020-10-19 18:00:47', null);
INSERT INTO `businessweb_banner` VALUES ('49', null, null, null, '2020-10-19 18:00:47', '2020-10-19 18:00:47', null);
INSERT INTO `businessweb_banner` VALUES ('50', null, null, null, '2020-10-19 18:00:47', '2020-10-19 18:00:47', null);
INSERT INTO `businessweb_banner` VALUES ('51', null, null, null, '2020-10-19 18:00:47', '2020-10-19 18:00:47', null);
INSERT INTO `businessweb_banner` VALUES ('52', null, null, null, '2020-10-19 18:00:48', '2020-10-19 18:00:48', null);
INSERT INTO `businessweb_banner` VALUES ('53', null, null, null, '2020-10-19 18:00:48', '2020-10-19 18:00:48', null);
INSERT INTO `businessweb_banner` VALUES ('54', null, null, null, '2020-10-19 18:00:48', '2020-10-19 18:00:48', null);
INSERT INTO `businessweb_banner` VALUES ('55', null, null, null, '2020-10-19 18:00:48', '2020-10-19 18:00:48', null);
INSERT INTO `businessweb_banner` VALUES ('56', null, null, null, '2020-10-19 18:00:48', '2020-10-19 18:00:48', null);
INSERT INTO `businessweb_banner` VALUES ('57', null, null, null, '2020-10-19 18:00:48', '2020-10-19 18:00:48', null);
INSERT INTO `businessweb_banner` VALUES ('58', null, null, null, '2020-10-19 18:00:48', '2020-10-19 18:00:48', null);
INSERT INTO `businessweb_banner` VALUES ('59', null, null, null, '2020-10-19 18:00:48', '2020-10-19 18:00:48', null);
INSERT INTO `businessweb_banner` VALUES ('60', null, null, null, '2020-10-19 18:00:48', '2020-10-19 18:00:48', null);
INSERT INTO `businessweb_banner` VALUES ('61', null, null, null, '2020-10-19 18:00:48', '2020-10-19 18:00:48', null);
INSERT INTO `businessweb_banner` VALUES ('62', null, null, null, '2020-10-19 18:00:49', '2020-10-19 18:00:49', null);
INSERT INTO `businessweb_banner` VALUES ('63', '2', 'local/public/businessweb/', 'b1603105515.jpg', '2020-10-19 18:05:15', '2020-10-19 18:05:15', null);
INSERT INTO `businessweb_banner` VALUES ('65', null, null, null, '2020-10-19 18:06:02', '2020-10-19 18:06:02', null);
INSERT INTO `businessweb_banner` VALUES ('66', null, null, null, '2020-10-19 18:06:02', '2020-10-19 18:06:02', null);
INSERT INTO `businessweb_banner` VALUES ('67', null, null, null, '2020-10-19 18:06:02', '2020-10-19 18:06:02', null);
INSERT INTO `businessweb_banner` VALUES ('68', null, null, null, '2020-10-19 18:06:02', '2020-10-19 18:06:02', null);
INSERT INTO `businessweb_banner` VALUES ('69', null, null, null, '2020-10-19 18:06:02', '2020-10-19 18:06:02', null);
INSERT INTO `businessweb_banner` VALUES ('70', null, null, null, '2020-10-19 18:06:02', '2020-10-19 18:06:02', null);
INSERT INTO `businessweb_banner` VALUES ('71', null, null, null, '2020-10-19 18:06:03', '2020-10-19 18:06:03', null);
INSERT INTO `businessweb_banner` VALUES ('72', null, null, null, '2020-10-19 18:06:03', '2020-10-19 18:06:03', null);
INSERT INTO `businessweb_banner` VALUES ('73', null, null, null, '2020-10-19 18:06:03', '2020-10-19 18:06:03', null);
INSERT INTO `businessweb_banner` VALUES ('74', null, null, null, '2020-10-19 18:06:03', '2020-10-19 18:06:03', null);
INSERT INTO `businessweb_banner` VALUES ('75', null, null, null, '2020-10-19 18:06:03', '2020-10-19 18:06:03', null);
INSERT INTO `businessweb_banner` VALUES ('76', null, null, null, '2020-10-19 18:06:03', '2020-10-19 18:06:03', null);
INSERT INTO `businessweb_banner` VALUES ('77', null, null, null, '2020-10-19 18:06:03', '2020-10-19 18:06:03', null);
INSERT INTO `businessweb_banner` VALUES ('78', null, null, null, '2020-10-19 18:06:03', '2020-10-19 18:06:03', null);
INSERT INTO `businessweb_banner` VALUES ('79', null, null, null, '2020-10-19 18:06:03', '2020-10-19 18:06:03', null);
INSERT INTO `businessweb_banner` VALUES ('80', null, null, null, '2020-10-19 18:06:04', '2020-10-19 18:06:04', null);
INSERT INTO `businessweb_banner` VALUES ('81', null, null, null, '2020-10-19 18:06:04', '2020-10-19 18:06:04', null);
INSERT INTO `businessweb_banner` VALUES ('82', null, null, null, '2020-10-19 18:06:04', '2020-10-19 18:06:04', null);
INSERT INTO `businessweb_banner` VALUES ('83', null, null, null, '2020-10-19 18:06:04', '2020-10-19 18:06:04', null);
INSERT INTO `businessweb_banner` VALUES ('84', null, null, null, '2020-10-19 18:06:05', '2020-10-19 18:06:05', null);
INSERT INTO `businessweb_banner` VALUES ('85', null, null, null, '2020-10-19 18:06:05', '2020-10-19 18:06:05', null);
INSERT INTO `businessweb_banner` VALUES ('86', null, null, null, '2020-10-19 18:06:05', '2020-10-19 18:06:05', null);
INSERT INTO `businessweb_banner` VALUES ('87', null, null, null, '2020-10-19 18:06:05', '2020-10-19 18:06:05', null);
INSERT INTO `businessweb_banner` VALUES ('88', null, null, null, '2020-10-19 18:06:05', '2020-10-19 18:06:05', null);
INSERT INTO `businessweb_banner` VALUES ('89', null, null, null, '2020-10-19 18:06:06', '2020-10-19 18:06:06', null);
INSERT INTO `businessweb_banner` VALUES ('90', null, null, null, '2020-10-19 18:06:06', '2020-10-19 18:06:06', null);
INSERT INTO `businessweb_banner` VALUES ('91', null, null, null, '2020-10-19 18:06:06', '2020-10-19 18:06:06', null);
INSERT INTO `businessweb_banner` VALUES ('92', null, null, null, '2020-10-19 18:06:06', '2020-10-19 18:06:06', null);
INSERT INTO `businessweb_banner` VALUES ('93', null, null, null, '2020-10-19 18:06:06', '2020-10-19 18:06:06', null);
INSERT INTO `businessweb_banner` VALUES ('94', null, null, null, '2020-10-19 18:06:06', '2020-10-19 18:06:06', null);
INSERT INTO `businessweb_banner` VALUES ('95', null, null, null, '2020-10-19 18:06:06', '2020-10-19 18:06:06', null);
INSERT INTO `businessweb_banner` VALUES ('96', null, null, null, '2020-10-19 18:06:06', '2020-10-19 18:06:06', null);
INSERT INTO `businessweb_banner` VALUES ('97', null, null, null, '2020-10-19 18:06:06', '2020-10-19 18:06:06', null);
INSERT INTO `businessweb_banner` VALUES ('98', null, null, null, '2020-10-19 18:06:06', '2020-10-19 18:06:06', null);
INSERT INTO `businessweb_banner` VALUES ('99', null, null, null, '2020-10-19 18:06:07', '2020-10-19 18:06:07', null);
INSERT INTO `businessweb_banner` VALUES ('100', null, null, null, '2020-10-19 18:06:07', '2020-10-19 18:06:07', null);
INSERT INTO `businessweb_banner` VALUES ('101', null, null, null, '2020-10-19 18:06:07', '2020-10-19 18:06:07', null);
INSERT INTO `businessweb_banner` VALUES ('102', null, null, null, '2020-10-19 18:06:07', '2020-10-19 18:06:07', null);
INSERT INTO `businessweb_banner` VALUES ('103', null, null, null, '2020-10-19 18:06:07', '2020-10-19 18:06:07', null);

-- ----------------------------
-- Table structure for `ck_backend_menu`
-- ----------------------------
DROP TABLE IF EXISTS `ck_backend_menu`;
CREATE TABLE `ck_backend_menu` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) DEFAULT NULL,
  `icon` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `url` varchar(150) CHARACTER SET latin1 DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `ref` int(11) DEFAULT '0',
  `ref2` int(11) DEFAULT '0',
  `menu_level` int(11) DEFAULT '1' COMMENT 'ระดับชั้นเมนู',
  `isActive` enum('Y','N') CHARACTER SET latin1 DEFAULT 'Y',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ck_backend_menu
-- ----------------------------
INSERT INTO `ck_backend_menu` VALUES ('1', 'AiCademy', 'bx bx-home', 'aicademy', '1', '0', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('2', 'เปิดคอร์สใหม่', 'bx bx-play', 'backend/course_event', '2', '1', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('3', 'รายละเอียดคอร์ส', 'bx bx-play', 'backend/course_details', '3', '1', '0', '1', 'N');
INSERT INTO `ck_backend_menu` VALUES ('4', 'บันทึกเข้าร่วมงาน', 'bx bx-play', 'backend/attendance_record', '4', '1', '0', '1', 'N');
INSERT INTO `ck_backend_menu` VALUES ('5', 'ประวัติการลงทะเบียน', 'bx bx-play', 'backend/course_history', '5', '1', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('6', 'Customer Service', 'bx bx-sitemap', 'customer_service', '6', '0', '0', '1', 'N');
INSERT INTO `ck_backend_menu` VALUES ('7', 'จำหน่ายสินค้าหน้าร้าน', 'bx bx-play', 'backend/selling_front_store', '7', '6', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('8', 'รายงานยอดขาย', 'bx bx-play', 'backend/sales_report', '8', '6', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('9', 'คำถามที่พบบ่อย', 'bx bx-play', 'backend/frequently_questions', '9', '6', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('10', 'ระบบจัดการ', 'bx bx-play', 'backend/management_system', '10', '6', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('11', 'CRM ของฉัน', 'bx bx-play', 'backend/my_crm', '11', '6', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('12', 'จัดการสินค้า', 'fas fa-cart-plus', 'manage_products', '12', '0', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('13', 'จัดการคลังสินค้า', 'bx bx-play', 'backend/manage_warehouse', '13', '12', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('14', 'สินค้ารอจัดส่ง', 'bx bx-play', 'backend/awaiting_delivery', '14', '12', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('15', 'ตรวจสอบการส่งสินค้า', 'bx bx-play', 'backend/check_delivery', '15', '12', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('16', 'จ่ายสินค้าตามเอกสาร', 'bx bx-play', 'backend/according_documents', '16', '12', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('17', 'รายการจ่ายสินค้าตามเอกสาร', 'bx bx-play', 'backend/doc_pay_list', '17', '12', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('18', 'รับสินค้าเข้าตาม PO', 'bx bx-play', 'backend/receive_according_po', '18', '12', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('19', 'คืนสินค้าตาม Invoice', 'bx bx-play', 'backend/according_invoice', '19', '12', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('20', 'อนุมัติคืนสินค้าตาม Invoice', 'bx bx-play', 'backend/approve_according_invoice', '20', '12', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('21', 'จ่ายสินค้าออก', 'bx bx-play', 'backend/pay_out', '21', '12', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('22', 'อนุมัติจ่ายสินค้าออก', 'bx bx-play', 'backend/approved_pay_out', '22', '12', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('23', 'สินค้าเบิก-ยืม', 'bx bx-play', 'backend/goods_picked_borrowed', '23', '12', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('24', 'อนุมัติสินค้าเบิก-ยืม', 'bx bx-play', 'backend/approve_picking_borrowed', '24', '12', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('25', 'เช็คสต๊อค (คลัง)', 'bx bx-play', 'backend/check_stock_warehouse', '25', '12', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('26', 'โอนสินค้าระหว่างคลัง', 'bx bx-play', 'backend/transfer_between_warehouses', '26', '12', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('27', 'อนุมัติโอนสินค้า', 'bx bx-play', 'backend/approve_product_transfer', '27', '12', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('28', 'รับสินค้าจากโอนย้าย', 'bx bx-play', 'backend/pick_up_transfer', '28', '12', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('29', 'Accounting', 'bx bx-book-content', 'accounting', '29', '0', '0', '1', 'N');
INSERT INTO `ck_backend_menu` VALUES ('30', 'เช็คสต๊อค (บัญชี)', 'bx bx-play', 'backend/check_stock_account', '30', '29', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('31', 'ตรวจรับเงินรายวัน', 'bx bx-play', 'backend/check_receive_money_daily', '31', '29', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('32', 'รายการภาษีหักประจำปี', 'bx bx-play', 'backend/annual_tax_deduction_items', '32', '29', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('33', 'รายการโอนค่าคอมมิชชั่น', 'bx bx-play', 'backend/commission_transfer_list', '33', '29', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('34', 'ค่าคอมมิชชั่น Ai-Stockist', 'bx bx-play', 'backend/stockist_commission', '34', '29', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('35', 'รายการโอนค่าคอมมิชชั่น AF', 'bx bx-play', 'backend/commission_transfer_list', '35', '29', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('36', 'ยอดรวมการขายไทยและกัมพูชา', 'bx bx-play', 'backend/total_sales', '36', '29', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('37', 'รายการเดินบัญชีกัมพูชา', 'bx bx-award', 'backend/cambodia_account_statement', '37', '29', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('38', 'รายการโอนสมาชิกนิติบุคคล', 'bx bx-play', 'backend/transfer_list_corporate', '38', '29', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('39', 'Setting ', 'bx bx-cog', 'setting_ai_smart_v3', '39', '0', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('40', 'ประเภทสินค้า', 'bx bx-play', 'backend/product_type', '40', '39', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('41', 'หน่วยสินค้า', 'bx bx-play', 'backend/product_unit', '41', '39', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('42', 'สินค้า', 'bx bx-play', 'backend/products', '42', '39', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('43', 'แพคเกจ', 'bx bx-play', 'backend/package', '43', '39', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('44', 'โบนัสค่าแนะนำ', 'bx bx-play', 'backend/fsb', '44', '39', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('45', 'โบนัสบริหารทีม', 'bx bx-play', 'backend/manage_bonus', '45', '39', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('46', 'คุณวุฒินักธุรกิจ', 'bx bx-play', 'backend/qualification', '46', '39', '0', '1', 'Y');
INSERT INTO `ck_backend_menu` VALUES ('47', 'สมาชิกระบบ (Admin)', 'bx bx-lock-open-alt', 'backend/admin', '47', '0', '0', '1', 'Y');

-- ----------------------------
-- Table structure for `ck_locale`
-- ----------------------------
DROP TABLE IF EXISTS `ck_locale`;
CREATE TABLE `ck_locale` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '(PK) ID ประเทศ',
  `locale` varchar(5) DEFAULT '0' COMMENT 'อักษรย่อประเทศ',
  `name` varchar(100) DEFAULT NULL COMMENT 'ชื่อประเทศ (ภาษาอังกฤษ) ',
  `url` varchar(255) DEFAULT NULL,
  `isActive` enum('Y','N') DEFAULT 'Y',
  `isShow` enum('Y','N') DEFAULT 'Y',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of ck_locale
-- ----------------------------

-- ----------------------------
-- Table structure for `ck_users_admin`
-- ----------------------------
DROP TABLE IF EXISTS `ck_users_admin`;
CREATE TABLE `ck_users_admin` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `locale_id` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'en' COMMENT 'อักษรย่อประเทศ',
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permission` int(11) DEFAULT '0' COMMENT '0=user,1=admin',
  `tel` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `isActive` enum('Y','N') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Y',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ck_users_admin
-- ----------------------------
INSERT INTO `ck_users_admin` VALUES ('1', 'en', 'Admin', 'admin@email.com', null, '$2y$10$MB/0xTAoMsXU64VJjV8/D.4UfVjGynGhUcdPfKfMR7l9vYGMXF6SG', null, '1', null, null, null, null, 'Y', '2020-06-22 08:33:29', '2020-07-15 04:49:25', null);
INSERT INTO `ck_users_admin` VALUES ('3', 'en', 'Sirisak', 'sir.t@hotmail.com', null, '$2y$10$yohPq0rvc/xsMcU/pabkyeUfdkYiRaiitjNHnOEfjeKPf0wK9PG0u', null, '0', '0864594116', 'Programmer', 'Programmer', null, 'Y', '2020-10-14 17:04:19', '2020-10-27 11:49:34', null);

-- ----------------------------
-- Table structure for `course_event`
-- ----------------------------
DROP TABLE IF EXISTS `course_event`;
CREATE TABLE `course_event` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ce_type` int(2) DEFAULT '0' COMMENT 'ประเภท cource/event',
  `ce_name` varchar(255) DEFAULT NULL COMMENT 'ชื่อกิจกรรม',
  `ce_place` varchar(255) DEFAULT NULL COMMENT 'สถานที่จัดงาน',
  `ce_max_ticket` int(11) DEFAULT '0' COMMENT 'จำนวนบัตรสูงสุด',
  `ce_ticket_price` decimal(10,2) DEFAULT NULL COMMENT 'ราคาบัตร (หน่วย: บาทไทย)',
  `ce_sdate` date DEFAULT NULL COMMENT 'วันเริ่มจำหน่าย',
  `ce_edate` date DEFAULT NULL COMMENT 'วันสิ้นสุดการจำหน่าย',
  `ce_features_booker` int(2) DEFAULT '0' COMMENT 'คุณสมบัติของผู้จอง',
  `ce_can_reserve` int(11) DEFAULT '0' COMMENT 'สมาชิก 1 คน สามารถจองได้(จำนวนบัตร)',
  `ce_limit` int(2) DEFAULT '0' COMMENT 'การจำกัดจำนวน',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of course_event
-- ----------------------------
INSERT INTO `course_event` VALUES ('1', '1', 'กิจกรรม ที่ 1', 'สถานที่จัดงาน 1', '1000', '500.00', '2020-10-27', '2020-10-28', '1', '1', '1', '2020-10-27 02:28:34', '2020-10-27 02:28:34', null);
INSERT INTO `course_event` VALUES ('2', '2', 'กิจกรรม ที่ 2', 'สถานที่จัดงาน 2', '2000', '1000.00', '2020-10-28', '2020-10-30', '3', '5', '2', '2020-10-27 02:30:19', '2020-10-27 02:30:19', null);

-- ----------------------------
-- Table structure for `course_history`
-- ----------------------------
DROP TABLE IF EXISTS `course_history`;
CREATE TABLE `course_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `course_event_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>course_event>id',
  `regis_date` date DEFAULT NULL COMMENT 'วันที่ลงทะเบียน',
  `amt_registered` int(11) DEFAULT '0' COMMENT 'จำนวนผู้ลงทะเบียน',
  `file_download` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of course_history
-- ----------------------------
INSERT INTO `course_history` VALUES ('1', '1', '2020-10-27', '999', 'f1.xlsx', '2020-10-27 02:39:23', null, null);
INSERT INTO `course_history` VALUES ('2', '2', '2020-10-27', '1990', 'f2.xlsx', '2020-10-27 02:39:25', null, null);

-- ----------------------------
-- Table structure for `course_history_list`
-- ----------------------------
DROP TABLE IF EXISTS `course_history_list`;
CREATE TABLE `course_history_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `course_event_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>course_event>id',
  `details` varchar(255) DEFAULT NULL,
  `regis_date` date DEFAULT NULL COMMENT 'วันที่ลงทะเบียน',
  `amt_registered` int(11) DEFAULT '0' COMMENT 'จำนวนผู้ลงทะเบียน',
  `file_download` varchar(255) DEFAULT NULL,
  `features` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of course_history_list
-- ----------------------------
INSERT INTO `course_history_list` VALUES ('1', '1', 'A123456 : 24Extra', '2020-10-27', '100', 'f1.xlsx', 'Bronze Star Award (BSA)', '2020-10-27 02:39:23', null, null);
INSERT INTO `course_history_list` VALUES ('2', '2', 'A123456 : 24Extra', '2020-10-27', '100', 'f2.xlsx', 'Silver Star Award (SSA)', '2020-10-27 02:39:25', null, null);

-- ----------------------------
-- Table structure for `dataset_banner_front`
-- ----------------------------
DROP TABLE IF EXISTS `dataset_banner_front`;
CREATE TABLE `dataset_banner_front` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `img_url` varchar(100) DEFAULT '',
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of dataset_banner_front
-- ----------------------------
INSERT INTO `dataset_banner_front` VALUES ('1', 'local/public/banner_front/', 'S_Turkey_2020.jpg', '2020-10-12 12:06:30', null, null);
INSERT INTO `dataset_banner_front` VALUES ('2', 'local/public/banner_front/', 'slide_ItemsPro-1.jpg', '2020-10-12 12:06:30', null, null);
INSERT INTO `dataset_banner_front` VALUES ('3', 'local/public/banner_front/', 'slide_VitaminCX-1.jpg', '2020-10-12 12:53:13', '2020-10-12 12:53:13', null);
INSERT INTO `dataset_banner_front` VALUES ('4', 'local/public/banner_front/', '1602482010.png', '2020-10-12 12:53:30', '2020-10-12 12:53:30', null);
INSERT INTO `dataset_banner_front` VALUES ('5', 'local/public/banner_front/', '1602642381.jpg', '2020-10-14 09:26:27', '2020-10-14 09:26:27', null);

-- ----------------------------
-- Table structure for `dataset_banner_slide`
-- ----------------------------
DROP TABLE IF EXISTS `dataset_banner_slide`;
CREATE TABLE `dataset_banner_slide` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `img_url` varchar(100) DEFAULT '',
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of dataset_banner_slide
-- ----------------------------
INSERT INTO `dataset_banner_slide` VALUES ('1', 'local/public/banner_slide/', 'S_Turkey_2020.jpg', '2020-10-12 12:06:30', null, null);
INSERT INTO `dataset_banner_slide` VALUES ('2', 'local/public/banner_slide/', 'slide_ItemsPro-1.jpg', '2020-10-12 12:06:30', null, null);
INSERT INTO `dataset_banner_slide` VALUES ('3', 'local/public/banner_slide/', 'slide_VitaminCX-1.jpg', '2020-10-12 12:53:13', '2020-10-12 12:53:13', null);
INSERT INTO `dataset_banner_slide` VALUES ('4', 'local/public/banner_slide/', '1602482010.png', '2020-10-12 12:53:30', '2020-10-12 12:53:30', null);
INSERT INTO `dataset_banner_slide` VALUES ('5', 'local/public/banner_slide/', '1602642491.jpg', '2020-10-14 09:28:16', '2020-10-14 09:28:16', null);

-- ----------------------------
-- Table structure for `dataset_ce_can_reserve`
-- ----------------------------
DROP TABLE IF EXISTS `dataset_ce_can_reserve`;
CREATE TABLE `dataset_ce_can_reserve` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `txt_desc` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of dataset_ce_can_reserve
-- ----------------------------
INSERT INTO `dataset_ce_can_reserve` VALUES ('1', '1', '2020-10-27 01:31:29', null, null);
INSERT INTO `dataset_ce_can_reserve` VALUES ('2', '2', '2020-10-27 01:31:29', null, null);
INSERT INTO `dataset_ce_can_reserve` VALUES ('3', '3', '2020-10-27 01:31:29', null, null);
INSERT INTO `dataset_ce_can_reserve` VALUES ('4', '4', '2020-10-27 01:31:29', null, null);
INSERT INTO `dataset_ce_can_reserve` VALUES ('5', '5', '2020-10-27 01:31:29', null, null);
INSERT INTO `dataset_ce_can_reserve` VALUES ('6', 'ไม่จำกัด', '2020-10-27 01:31:29', null, null);

-- ----------------------------
-- Table structure for `dataset_ce_features_booker`
-- ----------------------------
DROP TABLE IF EXISTS `dataset_ce_features_booker`;
CREATE TABLE `dataset_ce_features_booker` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `txt_desc` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of dataset_ce_features_booker
-- ----------------------------
INSERT INTO `dataset_ce_features_booker` VALUES ('1', 'บุคคลทั่วไป', '2020-10-27 01:29:56', null, null);
INSERT INTO `dataset_ce_features_booker` VALUES ('2', 'สมาชิกระดับ 1', '2020-10-27 01:29:56', null, null);
INSERT INTO `dataset_ce_features_booker` VALUES ('3', 'สมาชิกระดับ 2', '2020-10-27 01:29:56', null, null);
INSERT INTO `dataset_ce_features_booker` VALUES ('4', 'สมาชิกระดับ 3', '2020-10-27 01:29:56', null, null);
INSERT INTO `dataset_ce_features_booker` VALUES ('5', 'สมาชิกระดับ 4', '2020-10-27 01:29:56', null, null);
INSERT INTO `dataset_ce_features_booker` VALUES ('6', 'สมาชิกระดับ 5', '2020-10-27 01:29:56', null, null);

-- ----------------------------
-- Table structure for `dataset_ce_limit`
-- ----------------------------
DROP TABLE IF EXISTS `dataset_ce_limit`;
CREATE TABLE `dataset_ce_limit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `txt_desc` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of dataset_ce_limit
-- ----------------------------
INSERT INTO `dataset_ce_limit` VALUES ('1', 'ต่อวัน', '2020-10-27 01:32:11', null, null);
INSERT INTO `dataset_ce_limit` VALUES ('2', 'ต่อกิจกรรม', '2020-10-27 01:32:11', null, null);

-- ----------------------------
-- Table structure for `dataset_ce_type`
-- ----------------------------
DROP TABLE IF EXISTS `dataset_ce_type`;
CREATE TABLE `dataset_ce_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `txt_desc` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of dataset_ce_type
-- ----------------------------
INSERT INTO `dataset_ce_type` VALUES ('1', 'Course', '2020-10-27 01:28:50', null, null);
INSERT INTO `dataset_ce_type` VALUES ('2', 'Event', '2020-10-27 01:28:50', null, null);

-- ----------------------------
-- Table structure for `dataset_fast_start_bonus`
-- ----------------------------
DROP TABLE IF EXISTS `dataset_fast_start_bonus`;
CREATE TABLE `dataset_fast_start_bonus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `package_id_fk` int(11) DEFAULT NULL COMMENT 'ref>dataset_package>id',
  `g1` int(11) DEFAULT NULL,
  `g2` int(11) DEFAULT NULL,
  `g3` int(11) DEFAULT NULL,
  `g4` int(11) DEFAULT NULL,
  `g5` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of dataset_fast_start_bonus
-- ----------------------------
INSERT INTO `dataset_fast_start_bonus` VALUES ('1', '1', '25', '0', '0', '0', '0', '2020-10-16 15:36:07', null, null);
INSERT INTO `dataset_fast_start_bonus` VALUES ('2', '2', '50', '0', '0', '0', '0', '2020-10-16 15:36:07', null, null);
INSERT INTO `dataset_fast_start_bonus` VALUES ('3', '3', '50', '5', '5', '0', '0', '2020-10-16 15:36:07', null, null);
INSERT INTO `dataset_fast_start_bonus` VALUES ('4', '4', '50', '5', '5', '5', '0', '2020-10-19 10:24:32', '2020-10-19 10:24:32', null);
INSERT INTO `dataset_fast_start_bonus` VALUES ('5', '5', '50', '5', '5', '5', '10', '2020-10-19 10:47:18', '2020-10-19 10:47:18', null);

-- ----------------------------
-- Table structure for `dataset_manageteam_bonus`
-- ----------------------------
DROP TABLE IF EXISTS `dataset_manageteam_bonus`;
CREATE TABLE `dataset_manageteam_bonus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `package_id_fk` int(11) DEFAULT NULL COMMENT 'ref>dataset_package>id',
  `bonus_perday` int(11) DEFAULT NULL,
  `bonus_perround` int(11) DEFAULT NULL,
  `bonus_permonth` int(11) DEFAULT NULL,
  `benefit` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of dataset_manageteam_bonus
-- ----------------------------
INSERT INTO `dataset_manageteam_bonus` VALUES ('1', '1', '1000', '5000', '30000', 'ไม่ต้องรักษาผลประโยชน์และไม่เก็บคะแนน L/T (GPV)', '2020-10-19 10:47:36', '2020-10-19 10:47:36', null);
INSERT INTO `dataset_manageteam_bonus` VALUES ('2', '2', '10000', '50000', '300000', '500 PV / เดือน และเก็บคะแนน L/T (GPV)', '2020-10-16 15:36:07', null, null);
INSERT INTO `dataset_manageteam_bonus` VALUES ('3', '3', '15000', '75000', '450000', '500 PV / เดือน และเก็บคะแนน L/T (GPV)', '2020-10-16 15:36:07', null, null);
INSERT INTO `dataset_manageteam_bonus` VALUES ('4', '4', '20000', '100000', '600000', '500 PV / เดือน และเก็บคะแนน L/T (GPV)', '2020-10-16 15:36:07', null, null);
INSERT INTO `dataset_manageteam_bonus` VALUES ('5', '5', '35000', '175000', '1050000', '500 PV / เดือน และเก็บคะแนน L/T (GPV)', '2020-10-19 10:47:25', '2020-10-19 10:47:25', null);

-- ----------------------------
-- Table structure for `dataset_package`
-- ----------------------------
DROP TABLE IF EXISTS `dataset_package`;
CREATE TABLE `dataset_package` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dt_package` varchar(255) DEFAULT NULL,
  `dt_pv` int(11) DEFAULT NULL COMMENT 'อัพเกรด แบบที่ 1 คะแนนส่วนตัวภายใน 30 วัน',
  `dt_remark` varchar(255) DEFAULT NULL COMMENT 'อัพเกรด แบบที่ 2 โครงสร้างแนะนำตรง',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of dataset_package
-- ----------------------------
INSERT INTO `dataset_package` VALUES ('1', 'Member', '500', '', '2020-10-16 15:32:47', null, null);
INSERT INTO `dataset_package` VALUES ('2', 'Basic', '1000', 'Member แนะนำตรง member = 10 รหัส', '2020-10-16 15:32:47', null, null);
INSERT INTO `dataset_package` VALUES ('3', 'Supreme', '2500', 'Basic แนะนำตรง basic = 10 รหัส', '2020-10-16 15:32:47', null, null);
INSERT INTO `dataset_package` VALUES ('4', 'Jumbo', '5000', 'Supreme แนะนำตรง supreme = 10 รหัส', '2020-10-16 15:32:47', null, null);
INSERT INTO `dataset_package` VALUES ('5', 'Exclusive', '7000', 'Jumbo แนะนำตรง jumbo = 10 รหัส', '2020-10-16 15:32:47', null, null);

-- ----------------------------
-- Table structure for `dataset_product_type`
-- ----------------------------
DROP TABLE IF EXISTS `dataset_product_type`;
CREATE TABLE `dataset_product_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(100) NOT NULL,
  `product_type` varchar(255) DEFAULT NULL,
  `detail` text,
  `date_added` date DEFAULT NULL,
  `status` int(1) DEFAULT '0',
  `order` int(10) DEFAULT '0',
  `lang_id` int(10) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of dataset_product_type
-- ----------------------------
INSERT INTO `dataset_product_type` VALUES ('1', '1', 'สินค้าทั่วไป', 'รายละเอียดประเภทสินค้าที่ 1', '2020-10-12', '1', '1', '1', '2020-10-12 10:13:15', null, null);
INSERT INTO `dataset_product_type` VALUES ('2', '2', 'สินค้าแลกซื้อ / Gift Voucher', 'รายละเอียดประเภทสินค้าที่ 2', '2020-10-12', '1', '2', '1', '2020-10-12 10:13:15', null, null);
INSERT INTO `dataset_product_type` VALUES ('3', '3', 'คอร์สอบรม / บัตรเข้างาน', 'รายละเอียดประเภทสินค้าที่ 3', '2020-10-12', '1', '3', '1', '2020-10-12 10:13:15', null, null);
INSERT INTO `dataset_product_type` VALUES ('4', '4', 'ทริปท่องเที่ยว / Aiyara Around The World', 'รายละเอียดประเภทสินค้าที่ 4', '2020-10-12', '1', '4', '1', '2020-10-12 11:12:35', '2020-10-12 11:12:35', null);
INSERT INTO `dataset_product_type` VALUES ('5', '1', 'General merchandise', 'รายละเอียดประเภทสินค้าที่ 1', '2020-10-12', '1', '1', '2', '2020-10-12 10:13:15', null, null);
INSERT INTO `dataset_product_type` VALUES ('6', '2', 'Gift Voucher', 'รายละเอียดประเภทสินค้าที่ 2', '2020-10-12', '1', '2', '2', '2020-10-12 10:13:15', null, null);
INSERT INTO `dataset_product_type` VALUES ('7', '3', 'Training courses / tickets', 'รายละเอียดประเภทสินค้าที่ 3', '2020-10-12', '1', '3', '2', '2020-10-12 10:13:15', null, null);
INSERT INTO `dataset_product_type` VALUES ('8', '4', 'Travel Trip / Aiyara Around The World', 'รายละเอียดประเภทสินค้าที่ 4', '2020-10-12', '1', '4', '2', '2020-10-12 11:12:35', null, null);

-- ----------------------------
-- Table structure for `dataset_product_unit`
-- ----------------------------
DROP TABLE IF EXISTS `dataset_product_unit`;
CREATE TABLE `dataset_product_unit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_unit` varchar(255) DEFAULT NULL,
  `detail` text,
  `date_added` date DEFAULT NULL,
  `status` int(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of dataset_product_unit
-- ----------------------------
INSERT INTO `dataset_product_unit` VALUES ('1', 'หน่วยสินค้าที่ 1', 'รายละเอียดหน่วยสินค้าที่ 1', '2020-10-12', '1', '2020-10-12 10:13:15', null, null);
INSERT INTO `dataset_product_unit` VALUES ('2', 'หน่วยสินค้าที่ 2', 'รายละเอียดหน่วยสินค้าที่ 2', '2020-10-12', '1', '2020-10-12 10:13:15', null, null);
INSERT INTO `dataset_product_unit` VALUES ('3', 'หน่วยสินค้าที่ 3', 'รายละเอียดหน่วยสินค้าที่ 3', '2020-10-12', '1', '2020-10-12 10:13:15', null, null);
INSERT INTO `dataset_product_unit` VALUES ('4', 'หน่วยสินค้าที่ 4', 'รายละเอียดหน่วยสินค้าที่ 4', '2020-10-12', '1', '2020-10-12 11:43:17', '2020-10-12 11:43:17', null);

-- ----------------------------
-- Table structure for `dataset_qualification`
-- ----------------------------
DROP TABLE IF EXISTS `dataset_qualification`;
CREATE TABLE `dataset_qualification` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `business_qualifications` varchar(255) DEFAULT NULL,
  `pv_lt` int(11) DEFAULT NULL,
  `pv_mt` int(11) DEFAULT NULL,
  `basic_active_1` int(11) DEFAULT NULL,
  `basic_active_2` int(11) DEFAULT NULL,
  `ps_1` int(11) DEFAULT NULL,
  `ps_2` int(11) DEFAULT NULL,
  `amt_month` int(11) DEFAULT NULL,
  `bds_1` int(11) DEFAULT NULL,
  `bds_2` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of dataset_qualification
-- ----------------------------
INSERT INTO `dataset_qualification` VALUES ('1', 'Bronze Star Award (BSA)', '5000', '5000', '1', '1', '0', '0', '0', '0', '0', '1', '2020-10-16 15:44:22', null, null);
INSERT INTO `dataset_qualification` VALUES ('2', 'Silver Star Award (SSA)', '10000', '10000', '3', '3', '0', '0', '0', '0', '0', '1', '2020-10-16 15:44:22', null, null);
INSERT INTO `dataset_qualification` VALUES ('3', 'Gold Star Award (GSA)', '20000', '20000', '5', '5', '0', '0', '0', '0', '0', '1', '2020-10-16 15:44:22', null, null);
INSERT INTO `dataset_qualification` VALUES ('4', 'Double Gold Star (DGS)', '40000', '40000', '5', '5', '0', '0', '0', '0', '0', '1', '2020-10-16 15:44:22', null, null);
INSERT INTO `dataset_qualification` VALUES ('5', 'Tripple Gold Star (TGS)', '80000', '80000', '5', '5', '0', '0', '0', '0', '0', '1', '2020-10-16 15:44:22', null, null);
INSERT INTO `dataset_qualification` VALUES ('6', 'Platinum Star (PS)', '150000', '150000', '5', '5', '0', '0', '0', '0', '0', '1', '2020-10-16 15:44:22', null, null);
INSERT INTO `dataset_qualification` VALUES ('7', 'Perl Star (PES)', '250000', '250000', '5', '5', '0', '0', '0', '0', '0', '1', '2020-10-16 15:44:22', null, null);
INSERT INTO `dataset_qualification` VALUES ('8', 'Black Perl Star (BPS)', '350000', '350000', '5', '5', '0', '0', '0', '0', '0', '1', '2020-10-16 15:44:22', null, null);
INSERT INTO `dataset_qualification` VALUES ('9', 'Ruby Star (RUS)', '450000', '450000', '5', '5', '0', '0', '0', '0', '0', '1', '2020-10-16 15:44:22', null, null);
INSERT INTO `dataset_qualification` VALUES ('10', 'Sapphire Star (SAS)', '600000', '600000', '5', '5', '0', '0', '0', '0', '0', '1', '2020-10-16 15:44:22', null, null);
INSERT INTO `dataset_qualification` VALUES ('11', 'Blue Star (BLS)', '800000', '800000', '0', '0', '1', '1', '2', '0', '0', '1', '2020-10-16 15:44:22', null, null);
INSERT INTO `dataset_qualification` VALUES ('12', 'Emerald Star (EMS)', '1000000', '1000000', '0', '0', '2', '2', '2', '0', '0', '1', '2020-10-16 15:44:22', null, null);
INSERT INTO `dataset_qualification` VALUES ('13', 'Diamond Star (DS)', '1200000', '1200000', '0', '0', '3', '3', '3', '0', '0', '1', '2020-10-16 15:44:22', null, null);
INSERT INTO `dataset_qualification` VALUES ('14', 'Executive Diamond Star (EDS)', '1500000', '1500000', '0', '0', '4', '4', '3', '0', '0', '1', '2020-10-16 15:44:22', null, null);
INSERT INTO `dataset_qualification` VALUES ('15', 'Black Diamond Star (BDS)', '2800000', '2800000', '0', '0', '5', '5', '3', '0', '0', '1', '2020-10-16 15:44:22', null, null);
INSERT INTO `dataset_qualification` VALUES ('16', 'Double Diamond Star (DDS)', '0', '0', '0', '0', '0', '0', '0', '1', '1', '1', '2020-10-16 15:44:22', null, null);
INSERT INTO `dataset_qualification` VALUES ('17', 'Triple Diamond Star (TDS)', '0', '0', '0', '0', '0', '0', '0', '3', '3', '1', '2020-10-16 15:44:22', null, null);
INSERT INTO `dataset_qualification` VALUES ('18', 'Crown Diamond Star (CDS)', '0', '0', '0', '0', '0', '0', '0', '5', '5', '1', '2020-10-16 15:44:22', null, null);

-- ----------------------------
-- Table structure for `dataset_warehouse`
-- ----------------------------
DROP TABLE IF EXISTS `dataset_warehouse`;
CREATE TABLE `dataset_warehouse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `txt_desc` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of dataset_warehouse
-- ----------------------------
INSERT INTO `dataset_warehouse` VALUES ('1', 'คลังสินค้า', '2020-10-08 17:28:17', null, null);
INSERT INTO `dataset_warehouse` VALUES ('2', 'คลังย่อย', '2020-10-08 17:28:17', null, null);
INSERT INTO `dataset_warehouse` VALUES ('3', 'Zone', '2020-10-08 17:28:17', null, null);
INSERT INTO `dataset_warehouse` VALUES ('4', 'Shelf', '2020-10-08 17:28:17', null, null);

-- ----------------------------
-- Table structure for `menu_admin`
-- ----------------------------
DROP TABLE IF EXISTS `menu_admin`;
CREATE TABLE `menu_admin` (
  `id_menu_admin` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL,
  `main_menu_id` int(11) NOT NULL,
  `submenu_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_menu_admin`)
) ENGINE=MyISAM AUTO_INCREMENT=246 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of menu_admin
-- ----------------------------
INSERT INTO `menu_admin` VALUES ('1', '1', '1', '1', '2020-10-14 13:02:57', null, null);
INSERT INTO `menu_admin` VALUES ('245', '3', '11', null, '2020-10-27 11:58:04', null, null);
INSERT INTO `menu_admin` VALUES ('244', '3', '10', null, '2020-10-27 11:58:04', null, null);
INSERT INTO `menu_admin` VALUES ('243', '3', '9', null, '2020-10-27 11:58:04', null, null);
INSERT INTO `menu_admin` VALUES ('242', '3', '8', null, '2020-10-27 11:58:04', null, null);
INSERT INTO `menu_admin` VALUES ('241', '3', '7', null, '2020-10-27 11:58:04', null, null);
INSERT INTO `menu_admin` VALUES ('240', '3', '5', null, '2020-10-27 11:58:04', null, null);
INSERT INTO `menu_admin` VALUES ('239', '3', '2', null, '2020-10-27 11:58:04', null, null);
INSERT INTO `menu_admin` VALUES ('238', '3', '1', null, '2020-10-27 11:58:04', null, null);

-- ----------------------------
-- Table structure for `products`
-- ----------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_code` varchar(255) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `category_id` int(10) DEFAULT NULL,
  `pv` int(20) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `img_url` varchar(100) DEFAULT '',
  `image01` varchar(255) DEFAULT NULL,
  `image02` varchar(255) DEFAULT NULL,
  `image03` varchar(255) DEFAULT NULL,
  `image_default` int(1) DEFAULT '1' COMMENT '1=กำหนดให้เป็นรูป 01 เป็นรูปหลัก , 2=กำหนดให้เป็นรูป 02 เป็นรูปหลัก , 3=กำหนดให้เป็นรูป 03 เป็นรูปหลัก',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of products
-- ----------------------------
INSERT INTO `products` VALUES ('1', '00001', 'ปากทัชสกรีน', '2', '500', '100.00', 'local/public/products/', 'p11603739395.png', null, null, '1', '2020-10-27 02:09:55', '2020-10-27 02:09:55', null);
INSERT INTO `products` VALUES ('2', '00002', 'ริสแบนด์', '1', '500', '1000.00', 'product/general/ริสแบนด์.jpg', null, null, null, '1', '2020-10-08 18:00:48', null, null);
INSERT INTO `products` VALUES ('3', '00003', 'F_AIFACAD', '1', '500', '500.00', 'product/general/F_AIFACAD.jpg', null, null, null, '1', '2020-10-08 18:00:48', null, null);
INSERT INTO `products` VALUES ('4', '00004', 'ปากทัชสกรีน', '1', '500', '200.00', 'product/general/ปากทัชสกรีน.jpg', null, null, null, '1', '2020-10-08 18:00:48', null, null);
INSERT INTO `products` VALUES ('5', '00005', 'F_AIFACAD', '1', '500', '200.00', 'product/general/F_AIFACAD.jpg', null, null, null, '1', '2020-10-08 18:00:48', null, null);
INSERT INTO `products` VALUES ('6', '00006', 'ริสแบนด์', '1', '500', '100.00', 'product/general/ริสแบนด์.jpg', null, null, null, '1', '2020-10-08 18:00:48', null, null);
INSERT INTO `products` VALUES ('7', '00007', 'F_AIFACAD', '1', '500', '100.00', 'product/general/F_AIFACAD.jpg', null, null, null, '1', '2020-10-08 18:00:48', null, null);
INSERT INTO `products` VALUES ('8', '00008', 'ริสแบนด์', '2', '500', '100.00', 'product/general/ริสแบนด์.jpg', null, null, null, '1', '2020-10-08 18:00:48', null, null);
INSERT INTO `products` VALUES ('9', '00009', 'test', '3', '500', '100.00', 'local/public/products/', 'p11602646882.jpg', 'p21602646882.jpg', 'p31602646882.jpg', '2', '2020-10-14 10:41:37', '2020-10-14 10:41:37', null);

-- ----------------------------
-- Table structure for `product_detail`
-- ----------------------------
DROP TABLE IF EXISTS `product_detail`;
CREATE TABLE `product_detail` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `product_id` int(10) NOT NULL,
  `product_name` varchar(200) NOT NULL,
  `title` text,
  `description` text,
  `product_detail` text,
  `lang_id` int(10) DEFAULT NULL,
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of product_detail
-- ----------------------------
INSERT INTO `product_detail` VALUES ('1', '1', 'MATCHA GREEN TEA LATTE', 'เครื่องดื่มชนิดผงชาเขียวมัทฉะ ลาเต้', 'ผลิตภัณฑ์บำรุงผิว นวัตกรรมความงามใหม่ ที่เติมความชุ่มชื้นให้กับผิวได้อย่างล้ำลึก ด้วยประสิทธิภาพของเทคโนโลยี Hydrosal FreshCool ที่กักเก็บสารอาหารในเนื้อครีมและค่อยๆ ปล่อยสารออกมาช้าๆ ช่วยปกป้องและบำรุงผิว ฟื้นฟูผิวแห้งกร้าน พร้อมปรับสมดุลให้กับผิว แลดูกระจ่างใส ย้อนวัยดุจสาวรุ่น', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', '1', '2020-09-23 17:33:24', '2020-09-25 13:23:12');
INSERT INTO `product_detail` VALUES ('2', '2', 'Ailada In Love All in One Perfect Matte Lip', 'Ailada In Love All In One Perfect Matte Lip', 'ผลิตภัณฑ์บำรุงผิว นวัตกรรมความงามใหม่ ที่เติมความชุ่มชื้นให้กับผิวได้อย่างล้ำลึก ด้วยประสิทธิภาพของเทคโนโลยี Hydrosal FreshCool ที่กักเก็บสารอาหารในเนื้อครีมและค่อยๆ ปล่อยสารออกมาช้าๆ ช่วยปกป้องและบำรุงผิว ฟื้นฟูผิวแห้งกร้าน พร้อมปรับสมดุลให้กับผิว แลดูกระจ่างใส ย้อนวัยดุจสาวรุ่น', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', '1', '2020-09-23 17:33:30', '2020-09-25 13:23:13');
INSERT INTO `product_detail` VALUES ('3', '3', 'PERFECT CLEANSER', 'Perfect Cleanser', 'ผลิตภัณฑ์บำรุงผิว นวัตกรรมความงามใหม่ ที่เติมความชุ่มชื้นให้กับผิวได้อย่างล้ำลึก ด้วยประสิทธิภาพของเทคโนโลยี Hydrosal FreshCool ที่กักเก็บสารอาหารในเนื้อครีมและค่อยๆ ปล่อยสารออกมาช้าๆ ช่วยปกป้องและบำรุงผิว ฟื้นฟูผิวแห้งกร้าน พร้อมปรับสมดุลให้กับผิว แลดูกระจ่างใส ย้อนวัยดุจสาวรุ่น', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', '1', '2020-09-23 17:33:36', '2020-09-25 13:23:14');
INSERT INTO `product_detail` VALUES ('4', '4', 'AIBODY PROTECT PLUS MOISTURIZING SHAMPOO', 'ไอบอดี้ โพรเทค พลัส มอยส์เจอร์ไรซิ่ง แชมพู', 'ผลิตภัณฑ์บำรุงผิว นวัตกรรมความงามใหม่ ที่เติมความชุ่มชื้นให้กับผิวได้อย่างล้ำลึก ด้วยประสิทธิภาพของเทคโนโลยี Hydrosal FreshCool ที่กักเก็บสารอาหารในเนื้อครีมและค่อยๆ ปล่อยสารออกมาช้าๆ ช่วยปกป้องและบำรุงผิว ฟื้นฟูผิวแห้งกร้าน พร้อมปรับสมดุลให้กับผิว แลดูกระจ่างใส ย้อนวัยดุจสาวรุ่น', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', '1', '2020-09-23 17:33:39', '2020-09-25 13:23:16');
INSERT INTO `product_detail` VALUES ('5', '5', 'AIWORK', 'AIWORK', 'ผลิตภัณฑ์บำรุงผิว นวัตกรรมความงามใหม่ ที่เติมความชุ่มชื้นให้กับผิวได้อย่างล้ำลึก ด้วยประสิทธิภาพของเทคโนโลยี Hydrosal FreshCool ที่กักเก็บสารอาหารในเนื้อครีมและค่อยๆ ปล่อยสารออกมาช้าๆ ช่วยปกป้องและบำรุงผิว ฟื้นฟูผิวแห้งกร้าน พร้อมปรับสมดุลให้กับผิว แลดูกระจ่างใส ย้อนวัยดุจสาวรุ่น', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', '1', '2020-09-23 17:33:42', '2020-09-25 13:23:15');
INSERT INTO `product_detail` VALUES ('6', '6', 'SESAMIN SOAP', 'Ailada Soap', 'ผลิตภัณฑ์บำรุงผิว นวัตกรรมความงามใหม่ ที่เติมความชุ่มชื้นให้กับผิวได้อย่างล้ำลึก ด้วยประสิทธิภาพของเทคโนโลยี Hydrosal FreshCool ที่กักเก็บสารอาหารในเนื้อครีมและค่อยๆ ปล่อยสารออกมาช้าๆ ช่วยปกป้องและบำรุงผิว ฟื้นฟูผิวแห้งกร้าน พร้อมปรับสมดุลให้กับผิว แลดูกระจ่างใส ย้อนวัยดุจสาวรุ่น', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', '1', '2020-09-23 17:33:45', '2020-09-25 13:23:17');
INSERT INTO `product_detail` VALUES ('7', '7', '5 LECTURE', '5 LECTURE', 'ผลิตภัณฑ์บำรุงผิว นวัตกรรมความงามใหม่ ที่เติมความชุ่มชื้นให้กับผิวได้อย่างล้ำลึก ด้วยประสิทธิภาพของเทคโนโลยี Hydrosal FreshCool ที่กักเก็บสารอาหารในเนื้อครีมและค่อยๆ ปล่อยสารออกมาช้าๆ ช่วยปกป้องและบำรุงผิว ฟื้นฟูผิวแห้งกร้าน พร้อมปรับสมดุลให้กับผิว แลดูกระจ่างใส ย้อนวัยดุจสาวรุ่น', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', '1', '2020-09-23 17:33:47', '2020-09-25 13:23:18');
INSERT INTO `product_detail` VALUES ('8', '8', 'AIMMURA-X', 'DIETARY SUPPLEMENT PRODUCT', 'ผลิตภัณฑ์บำรุงผิว นวัตกรรมความงามใหม่ ที่เติมความชุ่มชื้นให้กับผิวได้อย่างล้ำลึก ด้วยประสิทธิภาพของเทคโนโลยี Hydrosal FreshCool ที่กักเก็บสารอาหารในเนื้อครีมและค่อยๆ ปล่อยสารออกมาช้าๆ ช่วยปกป้องและบำรุงผิว ฟื้นฟูผิวแห้งกร้าน พร้อมปรับสมดุลให้กับผิว แลดูกระจ่างใส ย้อนวัยดุจสาวรุ่น', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', '1', '2020-09-23 17:33:48', '2020-09-25 13:23:20');
INSERT INTO `product_detail` VALUES ('9', '9', 'MATCHA GREEN TEA LATTE', 'เครื่องดื่มชนิดผงชาเขียวมัทฉะ ลาเต้', 'ผลิตภัณฑ์บำรุงผิว นวัตกรรมความงามใหม่ ที่เติมความชุ่มชื้นให้กับผิวได้อย่างล้ำลึก ด้วยประสิทธิภาพของเทคโนโลยี Hydrosal FreshCool ที่กักเก็บสารอาหารในเนื้อครีมและค่อยๆ ปล่อยสารออกมาช้าๆ ช่วยปกป้องและบำรุงผิว ฟื้นฟูผิวแห้งกร้าน พร้อมปรับสมดุลให้กับผิว แลดูกระจ่างใส ย้อนวัยดุจสาวรุ่น', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', '1', '2020-09-23 17:33:50', '2020-09-25 13:23:19');
INSERT INTO `product_detail` VALUES ('10', '10', 'AIMMURA-X', 'DIETARY SUPPLEMENT PRODUCT', 'ผลิตภัณฑ์บำรุงผิว นวัตกรรมความงามใหม่ ที่เติมความชุ่มชื้นให้กับผิวได้อย่างล้ำลึก ด้วยประสิทธิภาพของเทคโนโลยี Hydrosal FreshCool ที่กักเก็บสารอาหารในเนื้อครีมและค่อยๆ ปล่อยสารออกมาช้าๆ ช่วยปกป้องและบำรุงผิว ฟื้นฟูผิวแห้งกร้าน พร้อมปรับสมดุลให้กับผิว แลดูกระจ่างใส ย้อนวัยดุจสาวรุ่น', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', '1', '2020-09-23 17:33:54', '2020-09-25 13:23:22');
