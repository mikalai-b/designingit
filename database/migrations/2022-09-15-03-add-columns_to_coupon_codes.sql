ALTER TABLE coupon_codes ADD redemption_count INT unsigned NOT NULL;
ALTER TABLE coupon_codes ADD redemption_limit INT unsigned DEFAULT NULL;