package com.mobile.azrinurvani.ojekonline.model;

import com.google.gson.annotations.SerializedName;

public class ResponseLoginRegister{

	@SerializedName("result")
	private String result;

	@SerializedName("msg")
	private String msg;

	@SerializedName("idUser")
	private String idUser;

	@SerializedName("data")
	private DataLoginRegis data;

	@SerializedName("token")
	private String token;

	public void setResult(String result){
		this.result = result;
	}

	public String getResult(){
		return result;
	}

	public void setMsg(String msg){
		this.msg = msg;
	}

	public String getMsg(){
		return msg;
	}

	public void setIdUser(String idUser){
		this.idUser = idUser;
	}

	public String getIdUser(){
		return idUser;
	}

	public void setData(DataLoginRegis data){
		this.data = data;
	}

	public DataLoginRegis getData(){
		return data;
	}

	public void setToken(String token){
		this.token = token;
	}

	public String getToken(){
		return token;
	}
}