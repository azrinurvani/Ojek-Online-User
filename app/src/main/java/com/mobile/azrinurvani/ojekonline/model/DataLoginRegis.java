package com.mobile.azrinurvani.ojekonline.model;

import com.google.gson.annotations.SerializedName;

public class DataLoginRegis{

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

	@SerializedName("user_register")
	private String userRegister;

	@SerializedName("user_level")
	private String userLevel;

	@SerializedName("id_user")
	private String idUser;

	@SerializedName("user_gcm")
	private Object userGcm;

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

	public void setUserRegister(String userRegister){
		this.userRegister = userRegister;
	}

	public String getUserRegister(){
		return userRegister;
	}

	public void setUserLevel(String userLevel){
		this.userLevel = userLevel;
	}

	public String getUserLevel(){
		return userLevel;
	}

	public void setIdUser(String idUser){
		this.idUser = idUser;
	}

	public String getIdUser(){
		return idUser;
	}

	public void setUserGcm(Object userGcm){
		this.userGcm = userGcm;
	}

	public Object getUserGcm(){
		return userGcm;
	}
}