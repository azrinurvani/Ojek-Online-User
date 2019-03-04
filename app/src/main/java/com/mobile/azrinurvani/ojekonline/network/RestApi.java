package com.mobile.azrinurvani.ojekonline.network;


import com.mobile.azrinurvani.ojekonline.model.ResponseBooking;
import com.mobile.azrinurvani.ojekonline.model.ResponseDetailDriver;
import com.mobile.azrinurvani.ojekonline.model.ResponseHistory;
import com.mobile.azrinurvani.ojekonline.model.ResponseLoginRegister;
import com.mobile.azrinurvani.ojekonline.model.ResponseWaitingDriver;
import com.mobile.azrinurvani.ojekonline.model.ResponseWayPoint;

import retrofit2.Call;
import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.GET;
import retrofit2.http.POST;
import retrofit2.http.Query;

public interface RestApi {

    @FormUrlEncoded
    @POST("daftar")
    Call<ResponseLoginRegister>registerUser(
            @Field("nama")String strnama,
            @Field("phone")String strphone,
            @Field("email")String stremail,
            @Field("password")String password
    );

    @FormUrlEncoded
    @POST("login")
    Call<ResponseLoginRegister>login(
            @Field("device")String strdevice,
            @Field("f_email")String stremail,
            @Field("f_password")String strpassword
    );

    //mengambil data dari API Google Maps
    @GET("json")
    Call<ResponseWayPoint> setRute(
            @Query("origin") String origin,
            @Query("destination")String destionation,
            @Query("key")String key
            );

    @FormUrlEncoded
    @POST("insert_booking")
    Call<ResponseBooking>insertBooking(
            @Field("f_device")String device,
            @Field("f_token")String token,
            @Field("f_jarak")Float jarak,
            @Field("f_idUser")String idUser,
            @Field("f_latAwal")String latawal,
            @Field("f_lngAwal")String lngawal,
            @Field("f_awal")String awal,
            @Field("f_latAkhir")String latakhir,
            @Field("f_lngAkhir")String lngakhir,
            @Field("f_akhir")String akhir,
            @Field("f_catatan")String catatan
    );

    @FormUrlEncoded
    @POST("checkBooking")
    Call<ResponseWaitingDriver>cekStatusDriver(
            @Field("idbooking")String idbooking
    );

    @FormUrlEncoded
    @POST("cancel_booking")
    Call<ResponseWaitingDriver>cancelBooking(
            @Field("idbooking")String idbooking,
            @Field("f_device")String device,
            @Field("f_token")String token
    );

    @FormUrlEncoded
    @POST("get_driver")
    Call<ResponseDetailDriver>detailDriver(
            @Field("f_iddriver")String iddriver
    );

    @FormUrlEncoded
    @POST("get_booking")
    Call<ResponseHistory>getDataHistory(
            @Field("f_token")String token,
            @Field("f_device")String device,
            @Field("status")String status,
            @Field("f_idUser")String iduser
            );


//    insert review dari user terhadap driver
    @FormUrlEncoded
    @POST("insert_review")
    Call<ResponseDetailDriver>review(
            @Field("f_token")String token,
            @Field("f_device")String device,
            @Field("f_idUser")String iduser,
            @Field("f_driver")String iddriver,
            @Field("f_idBooking")String idbooking,
            @Field("f_ratting")String rating,
            @Field("f_comment")String comment
            );

}