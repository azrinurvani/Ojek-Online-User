package com.mobile.azrinurvani.ojekonline.model;


import com.google.gson.annotations.SerializedName;

public class DataDetailDriver {

	@SerializedName("user_avatar")
	private Object userAvatar;

	@SerializedName("user_status")
	private String userStatus;

	@SerializedName("user_nama")
	private String userNama;

	@SerializedName("user_email")
	private String userEmail;

	@SerializedName("user_password")
	private String userPassword;

	@SerializedName("user_hp")
	private String userHp;

	@SerializedName("id_tracking")
	private String idTracking;

	@SerializedName("tracking_lng")
	private String trackingLng;

	@SerializedName("tracking_status")
	private String trackingStatus;

	@SerializedName("user_register")
	private String userRegister;

	@SerializedName("id_user")
	private String idUser;

	@SerializedName("tracking_waktu")
	private String trackingWaktu;

	@SerializedName("tracking_lat")
	private String trackingLat;

	@SerializedName("user_level")
	private String userLevel;

	@SerializedName("tracking_driver")
	private String trackingDriver;

	@SerializedName("user_gcm")
	private String userGcm;

	public void setUserAvatar(Object userAvatar){
		this.userAvatar = userAvatar;
	}

	public Object getUserAvatar(){
		return userAvatar;
	}

	public void setUserStatus(String userStatus){
		this.userStatus = userStatus;
	}

	public String getUserStatus(){
		return userStatus;
	}

	public void setUserNama(String userNama){
		this.userNama = userNama;
	}

	public String getUserNama(){
		return userNama;
	}

	public void setUserEmail(String userEmail){
		this.userEmail = userEmail;
	}

	public String getUserEmail(){
		return userEmail;
	}

	public void setUserPassword(String userPassword){
		this.userPassword = userPassword;
	}

	public String getUserPassword(){
		return userPassword;
	}

	public void setUserHp(String userHp){
		this.userHp = userHp;
	}

	public String getUserHp(){
		return userHp;
	}

	public void setIdTracking(String idTracking){
		this.idTracking = idTracking;
	}

	public String getIdTracking(){
		return idTracking;
	}

	public void setTrackingLng(String trackingLng){
		this.trackingLng = trackingLng;
	}

	public String getTrackingLng(){
		return trackingLng;
	}

	public void setTrackingStatus(String trackingStatus){
		this.trackingStatus = trackingStatus;
	}

	public String getTrackingStatus(){
		return trackingStatus;
	}

	public void setUserRegister(String userRegister){
		this.userRegister = userRegister;
	}

	public String getUserRegister(){
		return userRegister;
	}

	public void setIdUser(String idUser){
		this.idUser = idUser;
	}

	public String getIdUser(){
		return idUser;
	}

	public void setTrackingWaktu(String trackingWaktu){
		this.trackingWaktu = trackingWaktu;
	}

	public String getTrackingWaktu(){
		return trackingWaktu;
	}

	public void setTrackingLat(String trackingLat){
		this.trackingLat = trackingLat;
	}

	public String getTrackingLat(){
		return trackingLat;
	}

	public void setUserLevel(String userLevel){
		this.userLevel = userLevel;
	}

	public String getUserLevel(){
		return userLevel;
	}

	public void setTrackingDriver(String trackingDriver){
		this.trackingDriver = trackingDriver;
	}

	public String getTrackingDriver(){
		return trackingDriver;
	}

	public void setUserGcm(String userGcm){
		this.userGcm = userGcm;
	}

	public String getUserGcm(){
		return userGcm;
	}
}