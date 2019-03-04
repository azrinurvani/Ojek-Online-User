package com.mobile.azrinurvani.ojekonline.model;

import com.google.gson.annotations.SerializedName;


public class ResponseBooking{

	@SerializedName("result")
	private String result;

	@SerializedName("msg")
	private String msg;

	@SerializedName("id_booking")
	private int idBooking;

	@SerializedName("tarif")
	private int tarif;

	@SerializedName("waktu")
	private String waktu;

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

	public void setIdBooking(int idBooking){
		this.idBooking = idBooking;
	}

	public int getIdBooking(){
		return idBooking;
	}

	public void setTarif(int tarif){
		this.tarif = tarif;
	}

	public int getTarif(){
		return tarif;
	}

	public void setWaktu(String waktu){
		this.waktu = waktu;
	}

	public String getWaktu(){
		return waktu;
	}
}